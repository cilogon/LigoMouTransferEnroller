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

class LigoMouTransferEnroller extends AppModel {
  // Required by COmanage Plugins
  public $cmPluginType = "enroller";

  // Document foreign keys
  // Document foreign keys
  public $cmPluginHasMany = array(
    "CoEnrollmentFlowWedge" => "LigoMouTransferEnroller"
  );

  // Add behaviors
  public $actsAs = array('Containable', 'Changelog' => array('priority' => 5));

  // Association rules from this model to other models
  public $belongsTo = array("CoEnrollmentFlowWedge");

  public $hasMany = array(
    "LigoMouTransferEnroller.TransferPreserveAppointment" => array('dependent' => true),
    "LigoMouTransferPetition" => array("dependent" => true));

  // Default display field for cake generated views
  public $displayField = "co_enrollment_flow_wedge_id";

  // Validation rules for table elements
  public $validate = array(
    'co_enrollment_flow_wedge_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    ),
    'allow_edit' => array(
      'rule' => 'boolean',
      'required' => false,
      'allowEmpty' => true
    )
  );

  /**
   * Return CO Person Roles by status. Skip status if you want to fetch all roles
   *
   * @since  COmanage Registry v4.1.0
   * @param  Integer      $coId       CO  ID
   * @param  Integer      $co_person_id CO Person Id
   * @param  Array        $status     List of statuses to filter by
   * @return array|null   list of COUs
   */

  public function getPersonRolesByStatus($coId, $co_person_id, $status = array()) {
    $args                                              = array();
    $args['conditions']['CoPersonRole.status']         = $status;
    $args['conditions']['CoPersonRole.co_person_id']   = $co_person_id;
    $args['conditions']['Cou.co_id']                   = $coId;
    $args['conditions'][]                              = 'CoPersonRole.cou_id IS NOT null';
    $args['conditions'][]                              = 'CoPersonRole.co_person_role_id IS null';
    $args['conditions'][]                              = 'CoPersonRole.deleted IS NOT TRUE';
    $args['fields']                                    = array(
      'DISTINCT CoPersonRole.id',
      'CoPersonRole.title',
      'CoPersonRole.affiliation',
      'CoPersonRole.co_person_id',
      'CoPersonRole.cou_id',
      'CoPersonRole.valid_from',
      'CoPersonRole.valid_through',
    );
    $args['contain']                             = array(
      'Cou' => array(
        'fields'     => array('Cou.id', 'Cou.name'),
        'conditions' => array(
          'Cou.deleted IS NOT TRUE',
          'Cou.cou_id IS NULL',
          'Cou.co_id' => $coId,
        )
      )
    );

    $CoPersonRole = ClassRegistry::init('CoPersonRole');
    $CoPersonRole->Behaviors->unload('Changelog');
    $CoPersonRole->Cou->Behaviors->unload('Changelog');
    $roles = $CoPersonRole->find('all', $args);
    $CoPersonRole->Behaviors->load('Changelog');
    $CoPersonRole->Cou->Behaviors->load('Changelog');

    return $roles;
  }

  /**
   * Return Pending Role
   *
   * @since  COmanage Registry v4.1.0
   * @param  Integer      $coId          CO  ID
   * @param  Integer      $coPetitionId  CO Petition  ID
   * @return array|null   CoPersonRole
   */

  public function getCoPersonRoleFromPetition($coId, $coPetitionId) {
    $args                                        = array();
    $args['joins'][0]['table']                   = 'cm_co_petitions';
    $args['joins'][0]['alias']                   = 'CoPetition';
    $args['joins'][0]['type']                    = 'INNER';
    $args['joins'][0]['conditions'][0]           = 'CoPetition.cou_id=Cou.id';
    $args['joins'][0]['conditions'][1]           = 'CoPetition.id=' . $coPetitionId;
    $args['conditions'][]                        = "CoPetition.enrollee_co_person_id=CoPersonRole.co_person_id";
    $args['conditions'][]                        = 'CoPersonRole.co_person_role_id IS NULL';
    $args['conditions'][]                        = 'CoPersonRole.deleted IS NOT TRUE';

    $CoPersonRole = ClassRegistry::init('CoPersonRole');
    return $CoPersonRole->find('first', $args);
  }

  /**
   * Expose menu items.
   *
   * @since COmanage Registry v4.1.0
   * @return Array with menu location type as key and array of labels, controllers, actions as values.
   */

  public function cmPluginMenus() {
    return array();
  }
}