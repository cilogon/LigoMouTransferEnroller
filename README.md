# LIGO MOU Transfer COmanage Registry Plugin

Transfers are complicated by a number of factors: 
- There are four main types of groups: Simple MOU groups, federated MOU groups
- Each federated group will have multiple institutions, so a person may transfer within a federated group
- The LIGO Lab federated group has three statuses for a person: Lab MOU, Lab Personnel, Both
- There are two types of transfers processes: moving groups and joint appointments
- Transfers require coordination between the group being left and the group being joined
- Transfers change FTE number but must not violate FTE number policy

The Basic transfer scenarios are laid out in the following table
|*Scenario #*|*Original Group*|*New Group*        |
|------------|----------------|-------------------|
|1           |Simple          |Simple             |
|2           |Simple          |Federated          |
|3           |Federated       |Simple             |
|4           |Federated       |Same Federated     |
|5           |Federated       |Different Federated|

Scenario 1 : Transfer from simple MOU to simple MOU
Example: Graduate student graduates from UWM and gets post-doc at PSU. Aug. 30 is their last day at UWM and Sept. 1 is their first day at PSU.
Transfer is initiated graduate student on a date before Aug. 30. They will need to specify where they are moving to (new COU, PSU in this case), their first day in their new position (Sept. 1 in this case), and when the new position will end. There is also a comment box in which they can specify any extra information. There is a checkbox if they wish to keep membership in their old COU as well as being added to the new COU, which they will not check in this case since it is a transfer. When the form is submitted, a simple notification is sent to the admins group of their current COU (i.e UWM admins), and a notification of a new petition to join is sent to the admins group of the COU they are joining (PSU admins for this example). An admin of the new COU goes to an approval form where they can set efforts, position, and can edit the start and end date for the new COU membership. The default values for efforts are the maximum that the new member can have. The default for the end date is <should find a reasonable value here, might be position dependent>. If the new COU (PSU) admin approves the transfer petition, the graduate student is notified of the approval along with the approved values for start date, end date, position and effort values, and the old COU (UWM) admins are notified of the approval along with the start date for the new position. If the petition is denied, both the graduate student and the old COU (UWM) admins are notified. 

Case 2 : Joint appointment in two simple MOUs
Transferee wishes to maintain membership in current COU AND petition to join another COU. Same form as Case 1, except that this time the transferee checks the box to retain their existing membership in the old COU (UWM). For this case, if an admin of the new COU (PSU) approves the petition, the algorithm looks for any date in the joint appointment period when the enrollee is above the maximum allowed effort levels. If such a date exists, the plugin informs the PI of the new COU (PSU) that the effort levels they set cause the enrolee to exceed the maximum work effort, the approval step fails, and the PI of the new COU (PSU) is returned to the efforts setting screen to reset the effort values. Additionally, the plugin informs the new COU (PSU) PI of what the maximum available effort for the transferee in the new group are as of the first day of the joint appointement, and lists all groups the transferee belongs to along with their PIs. If new effort values are set in the form that do not cause the maximum effort of the transferee to be exceeded then the petition approval may proceed as above.

Case 3 : Transfer from federated MOU to simple MOU
Transferee is moving from a simple MOU to a federated group with no joint appointments. This case should be essentially identical to Case 1 (full transfer) or Case 2 (joint appointment) above.

Case 4 : Transfer from a simple MOU to a federated MOU
Example: Graduate student graduates from UWM and gets post-doc at Adelaide in OzGrav. Aug. 30 is their last day at UWM and Sept. 1 is their first day in OzGrav. 
Transfer is initiated by the graduate student on a date before Aug. 30. Additional to the information they provide in Case 1, they must also provide the OzGrav institution to which they are transferring. The rest of the transfer continues as in Case 1. While not a requirement at this time, if the plugin could allow us to configure an bespoke subset of COU admins for each institution within a federated COU (typically the COU PI and the COU admin(s) from the institution to which the transfer is happening) so that only that subset could approve or deny a transfer petition to the federated institution, that would probably be welcome.

Case 5 : Joint apointment from a simple MOU into a federated MOU
As in case 4, but with the added effort checking required for a joint appointment as in Case 2.

Case 6: Transfer from a federated MOU to another federated MOU
Transferee is moving from a federated group to another federated group, with no joint appointments. This case should be essentially identical to Case 4.

Case 7 : Transfer from simple MOU group to LIGO Lab
Example is from UWM to LIGO Lab MIT. Transfer is initiated by transferee. They will need to specify where they are moving to (new COU, LIGO Lab in this case, and the new instituion, MIT in this case), their first day in their new position, and when the new position will end. There is also a comment box in which they can specify any extra information. There is a checkbox if they wish to keep membership in their old COU as well as being added to the new COU, which they will not check in this case since it is a transfer. Although it is hidden, they are enrolling in both the LIGO Lab MOU COU and the LIGO Lab COU. When the form is submitted, a simple notification is sent to the admins group of their current COU (i.e UWM admins), and a notification of a new petition to join is sent to the admins group of the COU they are joining (LIGO Lab). An admin of the new COU goes to an approval form where they can set efforts, position, and remove the person from the LIGO Lab MOU (but still remain membership in LIGO Lab) and can edit the start and end date for the new COU membership. The default values for efforts are the maximum that the new member can have. The default for the end date is one year from the start date. If the new COU (Lab-MIT) admin approves the transfer petition, the transferee is notified of the approval along with the approved values for start date, end date, position and effort values, and the old COU (UWM) admins are notified of the approval along with the start date for the new position. If the petition is denied, both the graduate student and the old COU (UWM) admins are notified. 

Case 8 : 
