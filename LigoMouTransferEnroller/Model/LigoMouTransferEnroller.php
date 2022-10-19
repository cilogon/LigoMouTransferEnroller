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
  public $cmPluginHasMany = array();

  // Add behaviors
  public $actsAs = array('Containable', 'Changelog' => array('priority' => 5));

  // Association rules from this model to other models
  public $belongsTo = array("CoEnrollmentFlowWedge");

  public $hasMany = array("LigoMouTransferEnroller.TransferPreserveAppointment" => array('dependent' => true));

  // Default display field for cake generated views
  public $displayField = "co_enrollment_flow_wedge_id";

  // Validation rules for table elements
  public $validate = array(
    'co_enrollment_flow_wedge_id' => array(
      'rule' => 'numeric',
      'required' => true,
      'allowEmpty' => false
    )
  );

  /**
   * Return CO Person Active CO Person Roles
   *
   * @since  COmanage Registry v4.1.0
   * @param  Integer      $coId       CO  ID
   * @param  String       $identifier CO Person Identifier
   * @return array|null   list of COUs
   */

  public function getActivePersonRoles($coId, $identifier) {
    // Get COU memberships, array(id => name)
    $args = array();
    $args['joins'][0]['table'] = 'cm_co_person_roles';
    $args['joins'][0]['alias'] = 'CoPersonRole';
    $args['joins'][0]['type'] = 'INNER';
    $args['joins'][0]['conditions'][0] = 'CoPersonRole.cou_id=Cou.id';
    $args['joins'][1]['table'] = 'cm_identifiers';
    $args['joins'][1]['alias'] = 'Identifier';
    $args['joins'][1]['type'] = 'INNER';
    $args['joins'][1]['conditions'][0] = 'CoPersonRole.co_person_id=Identifier.co_person_id';
    $args['conditions']['Cou.co_id'] = $coId;
    $args['conditions']['CoPersonRole.status'] = StatusEnum::Active;
    $args['conditions'][] = 'CoPersonRole.cou_id IS NOT null';
    $args['conditions']['Identifier.identifier'] = $identifier;
    $args['conditions']['Identifier.status'] = StatusEnum::Active;
    $args['order'] = 'Cou.name ASC';
    $args['fields'] = array('Cou.id', 'Cou.name');
    $args['contain'] = false;

    $Cou = ClassRegistry::init('Cou');
    return $Cou->find('list', $args);
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