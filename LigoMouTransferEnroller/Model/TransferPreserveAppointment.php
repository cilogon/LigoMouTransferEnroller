<?php
/**
 * COmanage Registry Transfer Preserve Appointment Model
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

class TransferPreserveAppointment extends AppModel {
  // Add behaviors
  public $actsAs = array('Containable', 'Changelog' => array('priority' => 5));

  // Association rules from this model to other models
  public $belongsTo = array("LigoMouTransferEnroller.LigoMouTransferEnroller");

  // Validation rules for table elements
  public $validate = array(
    'ligo_mou_transfer_enroller_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'introduction' => array(
      'rule' => array('notBlank'),
      'required' => true,
      'allowEmpty' => false
    )
  );

  /**
   * Obtain the CO ID for a record.
   *
   * @since  COmanage Registry v4.1.0
   * @param  integer Record to retrieve for
   * @return integer Corresponding CO ID, or NULL if record has no corresponding CO ID
   * @throws InvalidArgumentException
   * @throws RunTimeException
   */

  public function findCoForRecord($id) {
    $ef = $this->findRecord($id);

    if(!empty($ef['LigoMouTransferEnroller']['CoEnrollmentFlowWedge']['CoEnrollmentFlow']['co_id'])) {
      return $ef['LigoMouTransferEnroller']['CoEnrollmentFlowWedge']['CoEnrollmentFlow']['co_id'];
    }

    return parent::findCoForRecord($id);
  }

  /**
   * Obtain Parent record.
   *
   * @since  COmanage Registry v4.1.0
   * @param  integer  Parent Id to retrieve record for
   * @param  integer  Child Id to retrieve record for
   * @return array    Records from Database
   * @throws InvalidArgumentException
   * @throws RunTimeException
   */

  public function findParentRecord($parentId = null, $childId = null) {
    if(empty($parentId) && empty($childId)) {
      return array();
    }

    $args = array();
    if(!empty($childId)) {
      $args['joins'][0]['table']                            = 'cm_transfer_preserve_appointments';
      $args['joins'][0]['alias']                            = 'TransferPreserveAppointment';
      $args['joins'][0]['type']                             = 'INNER';
      $args['joins'][0]['conditions'][0]                    = 'LigoMouTransferEnroller.id=TransferPreserveAppointment.ligo_mou_transfer_enroller_id';
      $args['conditions']['TransferPreserveAppointment.id'] = $childId;
    } else {
      $args['conditions']['LigoMouTransferEnroller.id'] = $parentId;
    }
    $args['contain'] = array('CoEnrollmentFlowWedge' => array('CoEnrollmentFlow'));

    return $this->LigoMouTransferEnroller->find('first', $args);
  }

  /**
   * Obtain the record.
   *
   * @since  COmanage Registry v4.1.0
   * @param  integer  Id to retrieve for
   * @return array    Corresponding Record and linked models
   * @throws InvalidArgumentException
   * @throws RunTimeException
   */

  public function findRecord($id) {
    // It's a long walk to the CO...

    $args = array();
    $args['conditions']['TransferPreserveAppointment.id'] = $id;
    $args['contain'] = array(
      'LigoMouTransferEnroller' => array(
        'CoEnrollmentFlowWedge' => array(
          'CoEnrollmentFlow'
        )
      )
    );

    return $this->find('first', $args);
  }
}