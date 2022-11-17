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

// For now we always allow edit
$e = true;

// Enumerate over all attributes defined for the enrollment flow associated with this petition.
// We do a series of <?php tags because we can't mix and match embedded tag styles.

$this->PetitionAttribute = $this->Helpers->load('LigoMouTransferEnroller.PetitionAttribute');

$attributes = $this->PetitionAttribute
                   ->retrieveTranserChoices($vv_wedge['id'], $vv_petition['id']);
$cfg = $this->PetitionAttribute->getCfg($vv_wedge['id']);

// Load custom css
$this->Html->css('LigoMouTransferEnroller.mou_transfer', array('inline' => false));
?>

<script type="text/javascript">
  const status = '<?php print $vv_petition['status'];?>';
  const allow_edit = <?php print $cfg["LigoMouTransferEnroller"]["allow_edit"];?>;
  const url_base = '<?php print $this->webroot;?>';
  const petition_id = <?php print $vv_petition['id']; ?>;
  const wedge_id = '<?php print $vv_wedge['id'];?>';
  const link_text = '<?php print _txt('op.edit') . _txt('pl.ligo_mou_transfer_enrollers.action-desc'); ?>';

  const edit_attributes_link = status == 'PA' && allow_edit &&
    `<div class="coAddEditButtons">
       <a href="${url_base}ligo_mou_transfer_enroller/ligo_mou_petition_attributes/edit/${petition_id}/wedgeid:${wedge_id}"
          class="editbutton"
          role="button">${link_text}
       </a>
     </div>`;

  $(function() {
    if(status == 'PA' && allow_edit) {
      $("#topLinks").append(edit_attributes_link);
    }
  });
</script>

<li id="tabs-ligo-attributes" class="fieldGroup">
  <?php if($cfg["LigoMouTransferEnroller"]["allow_edit"] === true
           && $vv_petition['status'] == PetitionStatusEnum::PendingApproval): ?>
  <div class="coAddEditButtons">
  <?php print $this->Html->link(
      _txt('op.edit') . _txt('pl.ligo_mou_transfer_enrollers.action-desc'),
      array(
        'plugin' => 'ligo_mou_transfer_enroller',
        'controller' => "ligo_mou_petition_attributes",
        'action' => 'edit',
        $vv_petition['id'],
        'wedgeid' => $vv_wedge['id']
      ),
      array('class' => 'editbutton')
    ) . PHP_EOL; ?>
  </div>
  <?php endif; ?>

  <a href="#tabs-ligo-attributes" class="fieldGroupName">
    <em class="material-icons">indeterminate_check_box</em>
    <?php print _txt('ct.ligo_mou_transfer_enrollers.1'); ?>
  </a>

  <div class="fields">
    <?php foreach($attributes as $attr): ?>
    <div class="modelbox">
      <div class="boxtitle">
        <?php
        print $this->Html->link($attr["Cou"]["name"],
                                array('controller' => 'co_person_roles',
                                      'action' => 'view',
                                  $attr["LigoMouTransferPetition"]["co_person_role_id"]),
                                array('class' => 'boxtitle-link'));
        ?>
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
            <?php print _txt('fd.membership'); ?>
          </div>
          <div class="modelbox-data-value">
            <?php print !$attr["LigoMouTransferPetition"]['maintain_membership'] ?
              _txt('fd.transfer_preserve_appointment.expire')
              :  _txt('fd.transfer_preserve_appointment.maintain'); ?>
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
