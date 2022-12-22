<?php
/**
 * COmanage Registry LIGO MOU Transfer Enroller CoPetitions Controller
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
 * @since         COmanage Registry v2.0.0
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

App::uses('CoPetitionsController', 'Controller');

class LigoMouTransferEnrollerCoPetitionsController extends CoPetitionsController {
  // Class name, used by Cake
  public $name = "LigoMouTransferEnrollerCoPetitionsController";

  public $uses = array(
    "CoPetition",
    "LigoMouTransferEnroller.LigoMouTransferEnroller"
  );

  /**
   * Plugin functionality following approve step
   *
   * @param   Integer  $id        CO Petition ID
   * @param   Array    $onFinish  URL, in Cake format
   */

  protected function execute_plugin_approve($id, $onFinish) {
    $args = array();
    $args['conditions']['LigoMouTransferEnroller.co_enrollment_flow_wedge_id'] = $this->viewVars['vv_efwid'];

    $ligo_enroller = $this->LigoMouTransferEnroller->find('first', $args);

    if(empty($ligo_enroller["LigoMouTransferPetition"])) {
      $this->redirect($onFinish);
    }

    $ligo_transfer_current_petitions = array_filter(
      $ligo_enroller["LigoMouTransferPetition"],
      static function ($petition) use ($id) {
        return $petition['co_petition_id'] == $id;
      }
    );

    if(empty($ligo_transfer_current_petitions)) {
      $this->redirect($onFinish);
    }

    // Get the petition
    $petition_record = $this->CoPetition->find('first', array(
      'conditions' => array('CoPetition.id' => $id),
      'contain' => false
    ));

    // The petition is not approved. Return to the Petition
    if($petition_record['CoPetition']["status"] != PetitionStatusEnum::Approved) {
      $this->redirect($onFinish);
    }

    foreach($ligo_transfer_current_petitions as $handle_petition) {
      // This membership will be preserved. Continue
      if($handle_petition['maintain_membership']) {
        continue;
      }
      // Get the latest valid from date. Even though i updated this on Petition Attributes step
      // the admin might have edited the dates.
      $valid_from = null;
      if ($handle_petition['mode'] === LigoMouTransferEnrollerTransferPolicyEnum::LeaveOnStart) {
        $args = array();
        $args["conditions"]["CoPersonRole.id"] = $petition_record["CoPetition"]["enrollee_co_person_role_id"];
        $args["contain"] = false;
        $role = $this->CoPetition->EnrolleeCoPerson->CoPersonRole->find("first", $args);
        $valid_from = $role["CoPersonRole"]["valid_from"];
      }

      $this->CoPetition->EnrolleeCoPerson->CoPersonRole->clear();
      $this->CoPetition->EnrolleeCoPerson->CoPersonRole->id = $handle_petition['co_person_role_id'];
      if(!$this->CoPetition->EnrolleeCoPerson
                           ->CoPersonRole
                           ->saveField('valid_through', $valid_from ?? $handle_petition['valid_through'])) {
        throw new RuntimeException(_txt('er.db.save'));
      }

      // Update plugin Role pick configuration
      $this->LigoMouTransferEnroller->LigoMouTransferPetition->clear();
      $this->LigoMouTransferEnroller->LigoMouTransferPetition->id = $handle_petition["id"];
      $this->LigoMouTransferEnroller->LigoMouTransferPetition->saveField("valid_through", $valid_from ?? $handle_petition['valid_through']);

      $history_msg = _txt('pl.ligo_mou_transfer_enrollers.role-update', array($handle_petition['co_person_role_id']));

      try {
        // Record history
        $this->CoPetition->EnrolleeCoPerson
                         ->CoPersonRole
                         ->HistoryRecord->record($petition_record['CoPetition']["enrollee_co_person_id"],
                                                 $handle_petition['co_person_role_id'],
                                                 null,
                                                 $this->Session->read('Auth.User.co_person_id'),
                                                 ActionEnum::CoPersonRoleEditedPetition,
                                                 $history_msg);
      } catch(Exception $e) {
        throw new RuntimeException(_txt('er.db.save'));
      }
    }

    // Finished the updates. Return to the petition
    $this->redirect($onFinish);
  }

  /**
   * Plugin functionality following finalize step
   *
   * @param   Integer  $id        CO Petition ID
   * @param   Array    $onFinish  URL, in Cake format
   */

  protected function execute_plugin_finalize($id, $onFinish) {
    // todo: Soft Delete the LigoMouTransfer Petition ???
    // Finished the updates. Return to the petition
    $this->redirect($onFinish);
  }

  /**
   * Plugin functionality following petitionerAttributes step
   *
   * @param   Integer  $id        CO Petition ID
   * @param   Array    $onFinish  URL, in Cake format
   */

  protected function execute_plugin_petitionerAttributes($id, $onFinish) {
    $args                                                                      = array();
    $args['conditions']['LigoMouTransferEnroller.co_enrollment_flow_wedge_id'] = $this->viewVars['vv_efwid'];
    $args['contain']                                                           = array('TransferPreserveAppointment');

    $ligo_enroller = $this->LigoMouTransferEnroller->find('first', $args);
    $this->set('vv_ligo_enroller', $ligo_enroller);
    $this->set('vv_petition_id', $id);

    $requested_roles = $this->LigoMouTransferEnroller->getCoPersonRoleFromPetition($this->cur_co['Co']['id'],
                                                                                   $id,
                                                                                   $this->Session->read('Auth.User.username'));
    if (empty($requested_roles)) {
      throw new RuntimeException("Bad result");
    }

    if ($this->request->is('post')
        && !empty($this->request->data["CoPetition"])) {

      $valid_from = $requested_roles['CoPersonRole']['valid_from'];
      foreach ($this->request->data["LigoMouTransferPetition"] as $idx => $record) {
        if ($record['mode'] === LigoMouTransferEnrollerTransferPolicyEnum::LeaveOnStart) {
          $this->request->data["LigoMouTransferPetition"][$idx]['valid_through'] = $valid_from;
        }
      }

      $LigoMouTransferPetition = ClassRegistry::init("LigoMouTransferPetition");
      if (!$LigoMouTransferPetition->saveMany($this->request->data["LigoMouTransferPetition"])) {
        throw new RuntimeException(_txt('er.db.save'));
      }

      $this->redirect($onFinish);
    }

    // GET Request
    $co_person_roles_active = $this->LigoMouTransferEnroller->getPersonRolesByStatus(
      $this->cur_co['Co']['id'],
      $this->Session->read('Auth.User.username'),
      array(StatusEnum::Active, StatusEnum::GracePeriod, StatusEnum::PendingApproval)
    );

    // The user has no Roles. Redirect on Finish
    if (empty($co_person_roles_active)) {
      $this->redirect($onFinish);
    }

    // Check for duplicates

    // Active
    $active_cou_memberships = Hash::extract($co_person_roles_active, '{n}.Cou.id');
    $active_cou_memberships_list = Hash::combine($co_person_roles_active, '{n}.Cou.id', '{n}.Cou.name');

    if(in_array($requested_roles['CoPersonRole']["cou_id"], $active_cou_memberships)) {
      $dbc = $this->CoPetition->getDataSource();
      $dbc->begin();
      try {
        $this->CoPetition->updateStatus($id,
                                        StatusEnum::Duplicate,
                                        $this->Session->read('Auth.User.co_person_id'));
      }
      catch(Exception $e) {
        $dbc->rollback();
        throw new RuntimeException($e->getMessage());
      }
      $dbc->commit();

      $cou_name = $active_cou_memberships_list[ $requested_roles['CoPersonRole']["cou_id"] ];
      $this->Flash->set(_txt('er.pt.dupe.cou-a',
                             array( $cou_name )),
                             array('key' => 'error'));
      $this->redirect(array('plugin' => null,
                            'controller' => 'co_people',
                            'action'     => 'canvas',
                             $this->Session->read('Auth.User.co_person_id')));
    }

    // Render Joint appointment view
    $this->set('vv_requested_cou', $requested_roles["Cou"]);
    $this->set('vv_person_roles', $co_person_roles_active);
    // Return in case of no configuration
    if (empty($ligo_enroller["TransferPreserveAppointment"])) {
      return;
    }

    $this->set('vv_introduction', $ligo_enroller["TransferPreserveAppointment"][0]["introduction"]);
  }
}