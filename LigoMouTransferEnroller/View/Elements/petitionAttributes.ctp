<?php
/**
 * COmanage Registry Petition Fields (used to display both petitions and petition-based invitations)
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
 * @link          https://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.7
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

// Enumerate over all attributes defined for the enrollment flow associated with this petition.
// We do a series of <?php tags because we can't mix and match embedded tag styles.

$this->PetitionAttribute = $this->Helpers->load('LigoMouTransferEnroller.PetitionAttribute');

$attributes = $this->PetitionAttribute
                   ->retrieveTranserChoices($vv_wedge['id'], $vv_petition['id']);

?>

<li id="tabs-ligo-attributes" class="fieldGroup">
  <a href="#tabs-ligo-attributes" class="fieldGroupName">
    <em class="material-icons">indeterminate_check_box</em>
    <?php print _txt('ct.ligo_mou_transfer_enrollers.1'); ?>
  </a>

  <div class="fields">
    <?php foreach($attributes as $attr): ?>
    <div class="modelbox">
      <div class="boxtitle">
        <?php print $attr["Cou"]["name"] ?>
        <?php if(!$attr["LigoMouTransferPetition"]['maintain_membership']): ?>
        <div class="desc">
          <?php print  _txt('fd.transfer_preserve_appointment.expires_at',
                            array(
                              !empty($attr["LigoMouTransferPetition"]['valid_through']) ? $this->Time->nice($attr["LigoMouTransferPetition"]['valid_through']) : _txt('fd.transfer_preserve_appointment.never'
                              ))); ?>
        </div>
        <?php endif; ?>
      </div>

      <div class="modelbox-data">
        <div class="modelbox-data-field">
          <div class="modelbox-data-label">
            <?php print "Membership"; ?>
          </div>
          <div class="modelbox-data-value">
            <?php print !$attr["LigoMouTransferPetition"]['maintain_membership'] ? "Expire" : "Maintain"; ?>
          </div>
        </div>
        <?php if(!$attr["LigoMouTransferPetition"]['maintain_membership']): ?>
        <div class="modelbox-data-field">
          <div class="modelbox-data-label">
            <?php print _txt('fd.transfer_preserve_appointment.mode-a'); ?>
          </div>
          <div class="modelbox-data-value">
            <?php print LigoMouTransferEnrollerTransferPolicyEnum::type[ $attr["LigoMouTransferPetition"]['mode'] ]; ?>
          </div>
        </div>
        <?php endif; ?>
      </div><!-- modelbox-data -->
      <span class="clearfix"></span>
    </div>
    <?php endforeach; ?>
  </div>
</li>
