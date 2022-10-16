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
  <div id="tabs-attributes">
    <div class="fields">
      <?php
      $e = true;
      // Render conclusion text for new petitions
      if(!empty($vv_introduction)) {
        print '<div class="modelbox d-flex"><div class="petition-attr-introduction">' . $vv_introduction . "</div></div>";
      }
      ?>

      <div class="modelbox">
        <div class="boxtitle"></div>
        <div class="modelbox-data">

        </div>
        <span class="clearfix"></span>
      </div>

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