<!--
/**
 * COmanage Registry LIGO MOU Transfer Enrollers Plugin Fields
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
-->
<?php
  // Determine if fields are editable
  $e = false;
  
  if(($this->action == "add" && $permissions['add']) || ($this->action == "edit" && $permissions['edit']))
    $e = true;
    
  // We shouldn't get here if we don't have at least read permission, but check just in case
  
  if(!$e && !$permissions['view'])
    return false;
  
  // Add breadcrumbs
  print $this->element("coCrumb");

  $args = array();
  $args['plugin'] = null;
  $args['controller'] = 'co_enrollment_flows';
  $args['action'] = 'index';
  $args['co'] = $cur_co['Co']['id'];
  $this->Html->addCrumb(_txt('ct.co_enrollment_flows.pl'), $args);

  $args = array();
  $args['plugin'] = null;
  $args['controller'] = 'co_enrollment_flows';
  $args['action'] = 'edit';
  $args[] = $transfer_preserve_appointments["CoEnrollmentFlowWedge"]["co_enrollment_flow_id"];
  $this->Html->addCrumb($transfer_preserve_appointments["CoEnrollmentFlowWedge"]['CoEnrollmentFlow']['name'], $args);

  $args = array();
  $args['plugin'] = null;
  $args['controller'] = 'co_enrollment_flow_wedges';
  $args['action'] = 'index';
  $args['coef'] = $transfer_preserve_appointments["CoEnrollmentFlowWedge"]["co_enrollment_flow_id"];
  $args[] = $transfer_preserve_appointments["CoEnrollmentFlowWedge"]['id'];
  $this->Html->addCrumb(_txt('ct.co_enrollment_flow_wedges.pl'), $args);

  $args = array();
  $args['plugin'] = null;
  $args['controller'] = 'co_enrollment_flow_wedges';
  $args['action'] = 'edit';
  $args[] = $transfer_preserve_appointments["CoEnrollmentFlowWedge"]['id'];
  $this->Html->addCrumb($transfer_preserve_appointments["CoEnrollmentFlowWedge"]['description'], $args);

  $crumbTxt = _txt('op.config');
  $this->Html->addCrumb($crumbTxt);

if($this->action != 'view') {
  // Populate cross references
  print $this->Form->hidden('ligo_mou_transfer_enroller_id', array('default' => $transfer_preserve_appointments["LigoMouTransferEnroller"]["id"])) . "\n";
}
?>


<ul id="<?php print $this->action; ?>_transfer_preserve_appointment" class="fields form-list">
  <li class="field-flex">
    <div class="field-name">
      <div class="field-title">
        <?php print ($e ? $this->Form->label('introduction', _txt('pl.transfer_preserve_appointment.introduction')) : _txt('pl.transferpreserveappointment.introduction')); ?>
        <span class="required">*</span>
      </div>
      <div class="field-desc"><?php print _txt('pl.transfer_preserve_appointment.introduction.desc'); ?></div>
    </div>
    <div class="field-info field-info-flex">
      <?php print ($e
        ? $this->Form->input('introduction', array('class' => 'focusFirst', 'type' => 'textarea'))
        : filter_var($transfer_preserve_appointments['TransferPreserveAppointment']['introduction'],FILTER_SANITIZE_SPECIAL_CHARS)); ?>
    </div>
  </li>
  <?php if($e): ?>
    <li class="fields-submit">
      <div class="field-name">
        <span class="required"><?php print _txt('fd.req'); ?></span>
      </div>
      <div class="field-info">
        <?php print $this->Form->submit($submit_label); ?>
      </div>
    </li>
  <?php endif; ?>
</ul>

<?php
print $this->element("changelog");