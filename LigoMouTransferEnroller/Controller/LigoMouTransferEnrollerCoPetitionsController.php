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


    if ($this->request->is('post')
        && !empty($this->request->data["CoPetition"])) {
      $requested_roles = $this->LigoMouTransferEnroller->getCoPersonRoleFromPetition($this->cur_co['Co']['id'],
                                                                                     $id,
                                                                                     $this->Session->read('Auth.User.username'));
      if (empty($requested_roles)) {
        throw new RuntimeException("Bad result");
      }

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
    $co_person_roles = $this->LigoMouTransferEnroller->getActivePersonRoles(
      $this->cur_co['Co']['id'],
      $this->Session->read('Auth.User.username')
    );

    // The user has no Roles. Redirect on Finish
    if (empty($co_person_roles)) {
      $this->redirect($onFinish);
    }

    $this->set('vv_person_roles', $co_person_roles);
    // Return in case of no configuration
    if (empty($ligo_enroller["TransferPreserveAppointment"])) {
      return;
    }

    $this->set('vv_introduction', $ligo_enroller["TransferPreserveAppointment"][0]["introduction"]);
  }
}