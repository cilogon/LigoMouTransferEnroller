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
   * Edit Action
   *
   * @since  COmanage Registry v4.1.0
   * @param  int   $wedgeId    Wedge Plugin Id
   */

  public function edit($id) {

    $args = array();
    $args['conditions']['CoPetition.id'] = $id;
    $args['contain'] = array(
      'CoPetitionAttribute' => array('CoEnrollmentAttribute'),
      'EnrolleeCoPerson' => array('PrimaryName','CoPersonRole'),
      'PetitionerCoPerson'
    );
    $co_petition =  $this->CoPetition->find('first', $args);

    // POST
    if($this->request->is('post')
       && !empty($this->request->data["LigoMouTransferEnroller"])) {
      try {
        // Update
        $this->LigoMouPetitionAttribute->updatePetitionAttributes($this->request->data["LigoMouTransferEnroller"],
                                                                  $this->Session->read('Auth.User.co_person_id'),
                                                                  $co_petition['CoPetition']['enrollee_co_person_id'],
                                                                  $co_petition['CoPetitionAttribute'],
                                                                  $co_petition["EnrolleeCoPerson"]["CoPersonRole"]);
        $this->Flash->set(_txt('rs.saved'), array('key' => 'success'));
      } catch (Exception $e) {
        $this->Flash->set($e->getMessage(), array('key' => 'error'));
      }

      $this->performRedirect();
    }

    if(empty($this->request->params["named"]["wedgeid"])) {
      throw new InvalidArgumentException(txt('er.ligo_mou_petition_attributes.wedgeid.specify'),
                                         HttpStatusCodesEnum::HTTP_BAD_REQUEST);
    }

    // GET
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

    $co_person_role = Hash::extract($co_petition, 'EnrolleeCoPerson.CoPersonRole.{n}[id=' . $co_petition['CoPetition']['enrollee_co_person_role_id'] . ']');

    $this->set('vv_co_person_role', $co_person_role);
    $this->set('title_for_layout', _txt('ct.ligo_mou_petition_attributes.pl'));
    $this->set('vv_co_petition', $co_petition);
    $this->set('vv_coe_attributes', $this->CoPetition->CoPetitionAttribute->CoEnrollmentAttribute->enrollmentFlowAttributes($co_petition["CoPetition"]["co_enrollment_flow_id"]));
    $this->set('vv_ligo_mou_transfer_petitions', $ligo_mou_transfer_petitions);
    $this->set('vv_ligo_mou_petition_attributes', $co_petition["CoPetitionAttribute"]);
    $this->set('vv_co_enrollment_flow_wedge_id', $this->request->params["named"]["wedgeid"]);
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
    $target['plugin'] = null;
    $target['controller'] = "co_petitions";
    $target['action'] = 'view';
    $target[]  = $this->request->data["LigoMouTransferEnroller"]["co_petition_id"];

    $this->redirect($target);
  }
}