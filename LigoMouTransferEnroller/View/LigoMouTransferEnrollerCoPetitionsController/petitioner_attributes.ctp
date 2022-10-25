<?php
/**
 * COmanage Registry Identifier Enroller Fields
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
 * @package       registry
 * @since         COmanage Registry v2.0.0
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

// Add breadcrumbs
print $this->element("coCrumb");
$this->Html->addCrumb(filter_var($title_for_layout,FILTER_SANITIZE_SPECIAL_CHARS));
// Load custom css
$this->Html->css('LigoMouTransferEnroller.mou_transfer', array('inline' => false));

$l = 0;

$args = array();
$args['url'] = array(
  'plugin'     => 'ligo_mou_transfer_enroller',
  'controller' => 'ligo_mou_transfer_enroller_co_petitions',
  'action'     => 'petitionerAttributes',
  filter_var($this->request->params['pass'][0],FILTER_SANITIZE_SPECIAL_CHARS)
);

print $this->Form->create('CoPetition', $args);

// Pass the token if we have one
if(!empty($vv_petition_token)) {
  print $this->Form->hidden('token', array('default' => $vv_petition_token));
}

print $this->Form->hidden('co_enrollment_flow_wedge_id', array('default' => $vv_efwid));
?>
  <script type="text/javascript">
    function handleVisibility(elem) {
      if($(elem).is(':checked')) {
        $(elem).parent().find('.transaction-mode').hide();
      } else {
        $(elem).parent().find('.transaction-mode').show();
      }
    }
  </script>


  <div id="tabs-attributes">
    <div class="fields">
      <?php
      $e = true;
      // Render conclusion text for new petitions
      if(!empty($vv_introduction)) {
        print '<div class="modelbox d-flex"><div class="petition-attr-introduction">' . $vv_introduction . "</div></div>";
      }
      ?>

      <!--  Active CO Person Roles  -->
      <?php foreach($vv_person_roles as $role): ?>
      <div class="modelbox">
        <div class="boxtitle">
          <?php print $role['Cou']['name'] ?>
          <div class="desc">
            <?php print  _txt('fd.transfer_preserve_appointment.expires_at',
                                              array(
                                                $role["CoPersonRole"]['valid_through'] ?? _txt('fd.transfer_preserve_appointment.never'
                                                ))); ?>
          </div>
        </div>

        <div class="modelbox-data">
          <div class="form-group">
            <?php
            print $this->Form->hidden("LigoMouTransferPetition.{$l}.cou_id", array('default' => $role['Cou']['id']));
            print $this->Form->hidden("LigoMouTransferPetition.{$l}.co_person_role_id", array('default' => $role["CoPersonRole"]["id"]));
            print $this->Form->hidden("LigoMouTransferPetition.{$l}.ligo_mou_transfer_enroller_id", array('default' => $vv_ligo_enroller["LigoMouTransferEnroller"]["id"]));
            print $this->Form->hidden("LigoMouTransferPetition.{$l}.co_enrollment_flow_wedge_id", array('default' => $vv_efwid));
            print $this->Form->hidden("LigoMouTransferPetition.{$l}.co_petition_id", array('default' => $vv_petition_id));
            print $this->Form->hidden("LigoMouTransferPetition.{$l}.valid_through", array('default' => date('Y-m-d H:i:s')));


            $attrs = array();
            $attrs['onClick'] = 'javascript:handleVisibility(this)';
            $attrs['checked'] = true;
            $attrs['class'] = 'form-check-input';

            print $this->Form->checkbox("LigoMouTransferPetition.{$l}.maintain_membership", $attrs);
            print $this->Form->label("LigoMouTransferPetition.{$l}.maintain_membership", _txt('fd.transfer_preserve_appointment.info')) . PHP_EOL;
            ?>
            <div class="transaction-mode" style="display: none">
              <?php
              $attrs = array();
              $attrs['empty'] = false;
              print $this->Form->label("LigoMouTransferPetition.{$l}.mode", _txt('fd.transfer_preserve_appointment.mode')) . PHP_EOL;
              print $this->Form->select("LigoMouTransferPetition.{$l}.mode",
                                        LigoMouTransferEnrollerTransferPolicyEnum::type,
                                        $attrs);

              if($this->Form->isFieldError("LigoMouTransferPetition.{$l}.mode")) {
                print $this->Form->error("LigoMouTransferPetition.{$l}.mode");
              }
            ?>
            </div>
          </div>
        </div><!-- modelbox-data -->
        <span class="clearfix"></span>
      </div>
      <?php
      $l++;
      endforeach;
      ?>


      <?php if($e): ?>
        <div id="<?php print $this->action; ?>_co_petition_attrs_submit" class="submit-box">
          <div class="required-info">
            <em><span class="required"><?php print _txt('fd.req'); ?></span></em><br />
          </div>
          <div class="submit-buttons">
            <?php print $this->Form->submit(_txt('op.submit')); ?>
          </div>
        </div>
      <?php endif; ?>
      <span class="clearfix"></span>
    </div>
  </div>
<?php
print $this->Form->end();