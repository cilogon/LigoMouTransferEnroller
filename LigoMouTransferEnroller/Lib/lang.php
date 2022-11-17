<?php
/**
 * COmanage Registry Identifier Enroller Plugin Language File
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
  
global $cm_lang, $cm_texts;

// When localizing, the number in format specifications (eg: %1$s) indicates the argument
// position as passed to _txt.  This can be used to process the arguments in
// a different order than they were passed.

$cm_ligo_mou_transfer_enroller_texts['en_US'] = array(
  // Titles, per-controller
  'ct.transfer_preserve_appointments.1'            => 'LIGO MOU Transfer Joint Appointment Configuration',
  'ct.transfer_preserve_appointments.pl'           => 'LIGO MOU Transfer Joint Appointment Configurations',
  'ct.ligo_mou_transfer_enrollers.1'               => 'LIGO MOU Transfer Appointment Enroller',
  'ct.ligo_mou_transfer_enrollers.pl'              => 'LIGO MOU Transfer Appointment Enrollers',
  'ct.ligo_mou_petition_attributes.1'              => 'LIGO MOU Petition Attribute',
  'ct.ligo_mou_petition_attributes.pl'             => 'LIGO MOU Petition Attributes',

  // Error texts
  'er.transfer_preserve_appointment.lmtid.specify' => 'Named parameter lmtid was not found',
  'er.transfer_preserve_appointment.id.specify'    => 'Id was not found',
  'er.transfer_preserve_appointment.validation'    => '"%1$s" Validation failed',

  // Fields
  'fd.ligo_mou_transfer_enrollers.allow_edit'       => 'Allow Edit',
  'fd.ligo_mou_transfer_enrollers.allow_edit.desc'  => 'Allow Enrollment Attributes edit from CO/COU Administrator',
  'fd.transfer_preserve_appointment.expires_at'     => 'Expires at: <b>%1$s</b>',
  'fd.transfer_preserve_appointment.never'          => 'Never',
  'fd.transfer_preserve_appointment.info'           => 'Uncheck to unsubscribe.',
  'fd.transfer_preserve_appointment.mode'           => 'Transaction Mode:',
  'fd.transfer_preserve_appointment.mode-a'         => 'Mode',
  'fd.transfer_preserve_appointment.expire'         => 'Expire',
  'fd.transfer_preserve_appointment.maintain'       => 'Maintain',

  // Actions
  'pl.ligo_mou_transfer_enrollers.role-update'      => 'Updated Co Person Role (%1$s) expiration date.',
  'pl.ligo_mou_transfer_enrollers.admin-edit'       => 'COU administrator edited "%1$s" field',

  // Plugin texts
  'pl.transfer_preserve_appointment.cou-info'          => 'You requested access to the COU <b>"%1$s"</b>',
  'pl.ligo_mou_transfer_enrollers.action-desc'         => ' Petition Attributes',
  'pl.transfer_preserve_appointment.introduction'      => 'Introduction',
  'pl.transfer_preserve_appointment.introduction.desc' => 'A short introductory text describing the purpose or other information about the Joint Appointment Configuration',
);
