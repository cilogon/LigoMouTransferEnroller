<?php
/**
 * COmanage Registry Ligo MOU Petition Attributes Controller
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

App::uses("StandardController", "Controller");

class LigoMouPetitionAttributesController extends StandardController {
  // Class name, used by Cake
  public $name = "LigoMouPetitionAttributes";

  // This controller needs a CO to be set
  public $requires_co = false;

  public $uses = array(
    // XXX LigoMouPetitionAttribute should go first!
    'LigoMouTransferEnroller.LigoMouPetitionAttribute',
    'LigoMouTransferEnroller.LigoMouTransferPetition',
    'LigoMouTransferEnroller.LigoMouTransferEnroller',
    'CoPetition'
  );

  /**
   * Determine the CO ID based on some attribute of the request.
   * This method is intended to be overridden by model-specific controllers.
   *
   * @since  COmanage Registry v4.1.0
   * @return Integer CO ID, or null if not implemented or not applicable.
   * @throws InvalidArgumentException
   */

//  protected function calculateImpliedCoId($data = null) {
//    $args = array();
//    $args['conditions']['CoPetition.id'] = $this->request->params["pass"][0];
//    $args['contain'] = false;
//    $co_petition =  $this->CoPetition->find('first', $args);
//
//    if(isset($co_petition["CoPetition"]["co_id"])) {
//      return $co_petition["CoPetition"]["co_id"];
//    }
//  }

  /**
   * Edit Action
   *
   * @since  COmanage Registry v4.1.0
   * @param  int   $wedgeId    Wedge Plugin Id
   */

  public function edit($id) {
    $args = array();
    $args['conditions']['LigoMouTransferEnroller.co_enrollment_flow_wedge_id'] = $this->request->params["named"]["wedgeid"];

    $pluginCfg =  $this->LigoMouTransferEnroller->find('first', $args);

    $ligo_mou_transfer_petitions = array();
    foreach($pluginCfg["LigoMouTransferPetition"] as $lmtp) {
      if($lmtp['co_enrollment_flow_wedge_id'] == $this->request->params["named"]["wedgeid"]
         && $lmtp['co_petition_id'] == $id) {
        $ligo_mou_transfer_petitions[] = $lmtp;
      }
    }

    $args = array();
    $args['conditions']['CoPetition.id'] = $id;
    $args['contain'] = array(
      'CoPetitionAttribute' => array('CoEnrollmentAttribute'),
      'EnrolleeCoPerson' => array('PrimaryName'),
      'PetitionerCoPerson'
    );
    $co_petition =  $this->CoPetition->find('first', $args);

    $this->set('title_for_layout', _txt('ct.ligo_mou_petition_attributes.pl'));
    $this->set('vv_co_petition', $co_petition);
    $this->set('vv_ligo_mou_transfer_petitions', $ligo_mou_transfer_petitions);
    $this->set('vv_ligo_mou_petition_attributes', $co_petition["CoPetitionAttribute"]);
    $this->set('vv_co_id', $co_petition["CoPetition"]["co_id"]);
  }

  /**
   * Authorization for this Controller, called by Auth component
   * - precondition: Session.Auth holds data used for authz decisions
   * - postcondition: $permissions set with calculated permissions
   *
   * @since  COmanage Registry v4.1.0
   * @return Array Permissions
   */

  function isAuthorized() {
    $roles = $this->Role->calculateCMRoles();

    // Construct the permission set for this user, which will also be passed to the view.
    $p = array();

    // Determine what operations this user can perform

    // Add
    $p['add'] = ($roles['cmadmin'] || $roles['coadmin'] || $roles['couadmin']);

    // Delete
    $p['delete'] = ($roles['cmadmin'] || $roles['coadmin'] || $roles['couadmin']);

    // Edit
    $p['edit'] = ($roles['cmadmin'] || $roles['coadmin'] || $roles['couadmin']);

    // View
    $p['index'] = ($roles['cmadmin'] || $roles['coadmin'] || $roles['couadmin']);

    // View
    $p['view'] = ($roles['cmadmin'] || $roles['coadmin'] || $roles['couadmin']);

    $this->set('permissions', $p);
    return($p[$this->action]);
  }

  /**
   * Perform a redirect back to the controller's default view.
   * - postcondition: Redirect generated
   *
   * @since  COmanage Registry v4.1.0
   */

  public function performRedirect() {
    $target = array();
    $target['controller'] = "co_petitions";
    $target['action'] = 'view';
    $target[]  = 10;

    $this->redirect($target);
  }
}