<?php
/**
 * COmanage Registry Identifier Enroller Identifiers Controller
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
 * @since         COmanage Registry v4.0.0
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

App::uses("StandardController", "Controller");

class TransferPreserveAppointmentsController extends StandardController
{
  // Class name, used by Cake
  public $name = "TransferPreserveAppointments";

  public $uses = array(
    // XXX TransferPreserveAppointment should go first!
    'LigoMouTransferEnroller.TransferPreserveAppointment',
    'LigoMouTransferEnroller.LigoMouTransferEnroller'
  );

  /**
   * Callback after controller methods are invoked but before views are rendered.
   * - precondition: Request Handler component has set $this->request
   *
   * @since  COmanage Registry v4.1.0
   */

  public function beforeRender() {
    parent::beforeRender();

    if(!in_array($this->action, array("add", "edit"))) {
      return;
    }

    // Pull the types from the parent table
    if (empty($this->request->params["named"]["lmtid"]) && $this->action == 'add') {
      throw new InvalidArgumentException(
        _txt('er.transfer_preserve_appointment.lmtid.specify'),
        HttpStatusCodesEnum::HTTP_BAD_REQUEST
      );
    }

    if (empty($this->request->params['pass'][0]) && $this->action == 'edit') {
      throw new InvalidArgumentException(
        _txt('er.transfer_preserve_appointment.id.specify'),
        HttpStatusCodesEnum::HTTP_BAD_REQUEST
      );
    }

    $ligo_mou_transfer_enroller = array();
    if(!empty($this->request->params["pass"][0])) {
      $ligo_mou_transfer_enroller = $this->TransferPreserveAppointment->findParentRecord(null, $this->request->params["pass"][0]);
    } else {
      $ligo_mou_transfer_enroller = $this->TransferPreserveAppointment->findParentRecord($this->request->params["named"]["lmtid"]);
    }

    if (empty($ligo_mou_transfer_enroller)) {
      throw new InvalidArgumentException(
        _txt(
          'er.notfound-b',
          array(_txt('ct.ligo_mou_transfer_enrollers.1'))
        )
      );
    }
    // Elector data filter configuration
    $this->set('transfer_preserve_appointments', $ligo_mou_transfer_enroller);
  }

  /**
   * Determine the CO ID based on some attribute of the request.
   * This method is intended to be overridden by model-specific controllers.
   *
   * @since  COmanage Registry v4.1.0
   * @return Integer CO ID, or null if not implemented or not applicable.
   * @throws InvalidArgumentException
   */

  protected function calculateImpliedCoId($data = null) {
    if(empty($this->request->params["named"]["lmtid"]) && $this->action == "add") {
      throw new InvalidArgumentException(_txt('er.transfer_preserve_appointment.lmtid.specify'), HttpStatusCodesEnum::HTTP_BAD_REQUEST);
    }

    if(empty($this->request->params["pass"][0]) && in_array($this->action, array("delete", "edit"))) {
      throw new InvalidArgumentException(_txt('er.ligo_mou_transfer_enrollers.id.specify'), HttpStatusCodesEnum::HTTP_BAD_REQUEST);
    }

    $ligo_mou_transfer_enroller = array();
    if(!empty($this->request->params["pass"][0])) {
      $ligo_mou_transfer_enroller = $this->TransferPreserveAppointment->findParentRecord(null, $this->request->params["pass"][0]);
    } else {
      $ligo_mou_transfer_enroller = $this->TransferPreserveAppointment->findParentRecord($this->request->params["named"]["lmtid"]);
    }

    if(empty($ligo_mou_transfer_enroller)) {
      if(!empty($this->request->params["pass"][0])) {
        throw new InvalidArgumentException(_txt('er.notfound',
                                                array(_txt('ct.transfer_preserve_appointments.1'),
                                                  filter_var($this->request->params["pass"][0],FILTER_SANITIZE_SPECIAL_CHARS))));
      }
      throw new InvalidArgumentException(_txt('er.notfound',
                                              array(_txt('ct.ligo_mou_transfer_enrollers.1'),
                                                filter_var($this->request->params["named"]["lmtid"],FILTER_SANITIZE_SPECIAL_CHARS))));
    }

    $coId = $ligo_mou_transfer_enroller["CoEnrollmentFlowWedge"]["CoEnrollmentFlow"]["co_id"];
    if($coId) {
      return $coId;
    }

    return parent::calculateImpliedCoId();
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
    $roles = $this->Role->calculateCMRoles();             // What was authenticated

    // Construct the permission set for this user, which will also be passed to the view.
    $p = array();

    // Determine what operations this user can perform

    // Add a new Identifier Enroller Identifier?
    $p['add'] = ($roles['cmadmin'] || $roles['coadmin']);

    // Delete an existing Identifier Enroller Identifier?
    $p['delete'] = ($roles['cmadmin'] || $roles['coadmin']);

    // Edit an existing Identifier Enroller Identifier?
    $p['edit'] = ($roles['cmadmin'] || $roles['coadmin']);

    // View all existing Identifier Enroller Identifiers?
    $p['index'] = ($roles['cmadmin'] || $roles['coadmin']);

    // View an existing Identifier Enroller Identifier?
    $p['view'] = ($roles['cmadmin'] || $roles['coadmin']);

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
    $target['plugin'] = 'ligo_mou_transfer_enroller';
    $target['controller'] = "ligo_mou_transfer_enrollers";
    $target['action'] = 'edit';
    if(!empty($this->request->query["lmtid"])) {
      $target[] = $this->request->query["lmtid"];
    } elseif (!empty($this->TransferPreserveAppointment->data['LigoMouTransferEnroller']['id'])) {
      $target[] = $this->TransferPreserveAppointment->data['LigoMouTransferEnroller']['id'];
    } else {
      $target[] = $this->data['TransferPreserveAppointment']['ligo_mou_transfer_enroller_id'];
    }

    $this->redirect($target);
  }
}