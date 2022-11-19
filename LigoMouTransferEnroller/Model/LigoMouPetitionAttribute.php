<?php
/**
 * COmanage Registry LIGO MOU Transfer Enroller Model
 *
 * Portions licensed to the University Corporation for Advanced Internet
 * Development, Inc. ("UCAID") under one or more contributor license agreements.
 * See the NOTICE file distributed with this work for additional information
 * regarding copyright ownership.
 *
 * UCAID licenses this file to you under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with the
 * License. You may obtain a copy of the License at:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry-plugin
 * @since         COmanage Registry v4.1.0
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

class LigoMouPetitionAttribute extends AppModel {
  // Class name, used by Cake
  public $name = "LigoMouPetitionAttribute";

  public $useTable = false;

  protected $_schema = array(
    'valid_from' => array(
      'type' => 'datetime',
      'null' => true,
    ),
    'valid_through' => array(
      'type' => 'datetime',
      'null' => true,
    ),
    'affiliation' => array(
      'type' => 'string',
      'null' => true,
      'length' => 32
    ),
  );

  /**
   * Update Petition Attributes and Co Person Models
   *
   * @since  COmanage Registry v4.1.0
   * @param  array   $request_data        POST Request data
   * @param  array   $actor               The Person performing the save
   * @param  array   $enrollee_id         The Person being modified
   * @param  array   $petitionAttributes  The current petitionAttributes values
   * @param  array   $personRoles         List of CO Person Roles
   * @throws RuntimeException
   */

  public function updatePetitionAttributes($request_data,
                                           $actor,
                                           $enrollee_id,
                                           $petitionAttributes,
                                           $personRoles) {
    $HistoryRecord = ClassRegistry::init('HistoryRecord');
    $CoPetition = ClassRegistry::init('CoPetition');
    $history_belongs_to = array_keys($HistoryRecord->belongsTo);

    // Parse the request data and extract
    // - current petition attributes
    // - pending for save data
    // - current person role values
    $pending_person_role = array();
    $form_data = array();
    $petition_data = array();


    foreach ($request_data as $key => $mydata) {
      if(!is_array($mydata)) {
        $petition_data[$key] = $mydata;
        if($key == 'cou_id') {
          // Extract the pending CO Person Role
          // XXX Hard coding the key is good enough for now
          $pending_person_role['EnrolleeCoPersonRole'] = Hash::extract($personRoles, '{n}[cou_id=' . (int)$mydata . ']')[0];
        }
        continue;
      }

      $form_data[] = $mydata;
    }

    $dbc = $this->getDataSource();
    $dbc->begin();
    // My models should always associate directly to CoPetition
    foreach ($form_data as $mydata) {
      $petition_attribute = '';
      foreach ($mydata as $mdl => $data) {
        // Check if the value has changed. If not continue without saving
        $tmp = Hash::extract($petitionAttributes, '{n}[id=' . (int)$data['id'] . ']');
        $current_record[$mdl] = array_pop($tmp);

        // XXX Petition Attributes will have id, value columns while the Person Record will have id, <field name>
        if( (isset($current_record[$mdl]['value']) && $current_record[$mdl]['value'] == $data['value'])  // Petition Attributes
            || (isset($pending_person_role[$mdl][$petition_attribute])
                && $pending_person_role[$mdl][$petition_attribute] == $data[$petition_attribute])                    // CO Person Role
        ) {
          // Cache the field name in order to use it during the next iteration
          $petition_attribute = $current_record[$mdl]['attribute'] ?? '';
          unset($current_record);
          continue;
        }

        try {
          $CoPetition->$mdl->clear();
          $CoPetition->$mdl->id = (int)$data['id'];
          unset($data['id']);
          foreach ($data as $name => $value) {
            if(!$CoPetition->$mdl->saveField($name, $value, array('validate' => true))) {
              $dbc->rollback();
              if(!empty($CoPetition->$mdl->invalidFields())) {
                $dbc->rollback();
                throw new RuntimeException(_txt('er.transfer_preserve_appointment.validation', array($mdl)));
              }
              $dbc->rollback();
              throw new RuntimeException(_txt('er.db.save-a', array($mdl)));
            }
            // XXX I need to update the valid_through column of the cm_ligo_mou_transfer_petitions table
            //     when the date is changed and the mode is `Leave on Membership start`
            if ($name == 'valid_through') {
              $LigoMouTransferPetition = ClassRegistry::init('LigoMouTransferEnroller.LigoMouTransferPetition');

              $args = array();
              $args['conditions']['LigoMouTransferPetition.co_enrollment_flow_wedge_id'] = $request_data["co_enrollment_flow_wedge_id"];
              $args['conditions']['LigoMouTransferPetition.co_petition_id'] = $request_data["co_petition_id"];
              $args['conditions']['LigoMouTransferPetition.mode'] = LigoMouTransferEnrollerTransferPolicyEnum::LeaveOnStart;
              $args['conditions'][] = 'LigoMouTransferPetition.maintain_membership IS NOT TRUE';
              $args['contain'] = false;

              $transfer_petitions =  $LigoMouTransferPetition->find('all', $args);

              foreach($transfer_petitions as $pt) {
                $LigoMouTransferPetition->clear();
                $LigoMouTransferPetition->id = (int)$pt['LigoMouTransferPetition']['id'];
                if(!$LigoMouTransferPetition->saveField($name, $value)) {
                  $dbc->rollback();
                  throw new RuntimeException(_txt('er.db.save-a', array('LigoMouTransferPetition')));
                }
              }
            }

            // Record history
            if(in_array($CoPetition->$mdl->name, $history_belongs_to)) {
              $action = constant('ActionEnum::' . $CoPetition->$mdl->name . 'EditedPetition');
              $CoPetition->$mdl->HistoryRecord->record($enrollee_id,
                                                       $CoPetition->$mdl->id, // XXX We currently handle only CoPersonRole
                                                       null,
                                                       $actor,
                                                       $action,
                                                       _txt('pl.ligo_mou_transfer_enrollers.admin-edit',
                                                            array($CoPetition->$mdl->name . ":" .$name)));
            }
          }
        } catch(Exception $e) {
          $dbc->rollback();
          throw new RuntimeException($e->getMessage());
        }
      }
    }
    $dbc->commit();
  }

  // Validation rules for table elements
  // Validation rules must be named 'content' for petition dynamic rule adjustment
  public $validate = array(
    'valid_from' => array(
      'content' => array(
        'rule' => array('validateTimestamp'),
        'required' => false,
        'allowEmpty' => true
      ),
      'precedes' => array(
        'rule' => array('validateTimestampRange', "valid_through", "<"),
      ),
    ),
    'valid_through' => array(
      'content' => array(
        'rule' => array('validateTimestamp'),
        'required' => false,
        'allowEmpty' => true
      ),
      'follows' => array(
        'rule' => array("validateTimestampRange", "valid_from", ">"),
      ),
    ),
    'affiliation' => array(
      'content' => array(
        'rule' => array('validateExtendedType',
          array('attribute' => 'affiliation',
                'default' => array(AffiliationEnum::Faculty,
                                   AffiliationEnum::Student,
                                   AffiliationEnum::Staff,
                                   AffiliationEnum::Alum,
                                   AffiliationEnum::Member,
                                   AffiliationEnum::Affiliate,
                                   AffiliationEnum::Employee,
                                   AffiliationEnum::LibraryWalkIn))),
        'required' => false,
        'allowEmpty' => true
      )
    ),
  );
}