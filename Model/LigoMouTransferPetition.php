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

class LigoMouTransferPetition extends AppModel {
  // Add behaviors
  public $actsAs = array('Containable');

  // Association rules from this model to other models
  public $belongsTo = array(
    "LigoMouTransferEnroller.LigoMouTransferEnroller",
    "Cou",
    "CoEnrollmentFlowWedge",
    "CoPetition"
  );

  // Validation rules for table elements
  public $validate = array(
    'ligo_mou_transfer_enroller_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'co_enrollment_flow_wedge_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'co_petition_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'cou_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'co_person_role_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'mode' => array(
      'rule' => array('inList', array(LigoMouTransferEnrollerTransferPolicyEnum::LeaveNow,
                                      LigoMouTransferEnrollerTransferPolicyEnum::LeaveOnStart)),
      'required' => true,
      'allowEmpty' => false
    ),
    'maintain_membership' => array(
      'rule' => array('boolean')
    ),
    'valid_through' => array(
      'content' => array(
        'rule' => array('validateTimestamp'),
        'required' => false,
        'allowEmpty' => true
      )
    )
  );

  /**
   * Obtain the Petition Transfer attributes
   *
   * @since  COmanage Registry v4.1.0
   * @param  integer Wedge Plugin Id
   * @param  integer Petition Id
   * @return array List of Petition Transfer Attributes
   */

  public function getPetitionTransferAttributes($wedgeId, $petitionId) {
    $args = array();
    $args['conditions']['LigoMouTransferPetition.co_enrollment_flow_wedge_id'] = $wedgeId;
    $args['conditions']['LigoMouTransferPetition.co_petition_id'] = $petitionId;
    $args['contain'] = true;

    return $this->find('all', $args);
  }
}