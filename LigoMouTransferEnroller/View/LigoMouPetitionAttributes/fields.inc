<!--
/**
 * COmanage Registry LIGO MOU Petition Attributes Fields
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
$args['controller'] = 'co_dashboards';
$args['action'] = 'index';
$args['co'] = $vv_co_id;
$this->Html->addCrumb(_txt('ct.co_dashboards.pl'), $args);

$args = array();
$args['plugin'] = null;
$args['controller'] = 'co_petitions';
$args['action'] = 'index';
$args['co'] = $vv_co_id;
$args['sort'] = 'modified';
$args['search.copersonid'] = $vv_co_petition["EnrolleeCoPerson"]['id'];

$this->Html->addCrumb(_txt('ct.petitions.pl'), $args);

$args = array();
$args['plugin'] = null;
$args['controller'] = 'co_petitions';
$args['action'] = 'view';
$args[] = $vv_co_petition['CoPetition']['id'];
$this->Html->addCrumb(generateCn($vv_co_petition["EnrolleeCoPerson"]["PrimaryName"]), $args);

$this->Html->addCrumb(_txt('ct.ligo_mou_petition_attributes.pl'));
$this->Html->addCrumb(_txt('op.edit'));

print $this->Form->hidden("LigoMouTransferEnroller.co_petition_id", array('default' => $vv_co_petition['CoPetition']['id'])) . PHP_EOL;

?>

<div class="co-info-topbox">
  <em class="material-icons">info</em>
  <?php print "Edit now"; ?>
</div>

<ul id="<?php print $this->action; ?>_ligo_mou_petition_attributes" class="fields form-list">
  <?php foreach($vv_ligo_mou_petition_attributes as $idx => $attribute): ?>
  <?php
    if($this->action != 'edit') {
      continue;
    }

    // For now we only care about dates
    if(strpos($attribute["CoEnrollmentAttribute"]["attribute"], 'r:valid') === false) {
      continue;
    }

    $attribute_field_name = $vv_available_attributes_restructured[$attribute["CoEnrollmentAttribute"]["attribute"]]['field'];

    print $this->Form->hidden("LigoMouTransferEnroller.{$idx}.CoPetitionAttribute.id", array('default' => $vv_co_petition["CoPetitionAttribute"][$idx]["id"])) . PHP_EOL;


    ?>
    <li class="modelbox-data">
      <div class="field-name">
        <?php
        if($e) {
          $model = $vv_available_attributes_restructured[$attribute["CoEnrollmentAttribute"]["attribute"]]['model'];
          print $this->Form->label("LigoMouTransferEnroller.{$idx}.{$model}.{$attribute_field_name}", _txt("fd.{$attribute_field_name}.tz", array($vv_tz)));
        } else {
          print _txt("fd.{$attribute_field_name}");
        }
        ?><br />
        <span class="field-descr"><?php print _txt("fd.{$attribute_field_name}.desc"); ?></span>
      </div>
      <div class="field-info">
        <?php
        if($e) {
          $args = array(
            'class' => 'datepicker-f'
          );

          if(!empty($attribute['value'])) {
            if(!empty($vv_tz)) {
              // We need to adjust the UTC value to the user's local time
              $args['value'] = $this->Time->format($attribute['value'], "%F %T", false, $vv_tz);
            } else {
              $args['value'] = $attribute['value'];
            }
          }

          print $this->Form->hidden("LigoMouTransferEnroller.{$idx}.CoPetitionAttribute.value",
                                    array('default' => $args['value'])) . PHP_EOL;
          if($model == 'CoPersonRole') {
            print $this->Form->hidden("LigoMouTransferEnroller.{$idx}.{$model}.id", array('default' => $vv_co_person_role[0]["id"])) . PHP_EOL;
          }
          print $this->Form->text("LigoMouTransferEnroller.{$idx}.{$model}.{$attribute_field_name}", $args);
        } else {
          if(!empty($attribute['value'])) {
            print $this->Time->format($attribute['value'], "%c $vv_tz", false, $vv_tz);
          }
        }
        ?>
      </div>
    </li>
  <?php endforeach; ?>


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
