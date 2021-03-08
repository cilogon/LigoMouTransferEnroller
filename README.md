# LIGO MOU Transfer COmanage Registry Plugin

Transfers are complicated by a number of factors: 
- There are three main types of groups: Simple MOU groups, Basic Federated MOU groups, LIGO Lab Federated groups
- Each federated group will have multiple institutions, so a person may transfer within a federated group
- The LIGO Lab federated group has three statuses for a person: Lab MOU, Lab Personnel, Both
- There are two types of transfers processes: those resulting in a person belonging to a single group and those resulting in a joint appointment
- Transfers require coordination between the group being left and the group being joined
- Transfers change effort (WC) numbers but must not violate effort number policy

To understand how these different factors affect the transfer process, we consider the following questions:

1. _**Who can initiate a transfer?**_
In all cases, the transferee initiates the transfer.
2. _**What information is supplied to initiate the transfer?**_ The information is split into three categories:
  * _Memberships that are ending_ - The transferee is given a list of groups to which they currently belong. The indicate (with, say, checkboxes) which, if any, groups they will be leaving. For each group they indicate they are leaving, they must specify an end date. 
  * _Memberships that are starting_ - The transferee is asked if they will be joining one or more new groups. If they answer affirmatively, they must specify:
    * Which group they are joining (preferably from a list of groups that includes all MOU groups except those of which they are already member or into which they are already transferring). 
    * If they select a federated group, they are given a list of institutions within that federation that they can join. 
    * If they are joining the LIGO Lab federated group, they are asked whether they want to be an LSC member. They default to Lab Personnel group and, if they indicate LSC member, to Lab LSC member. The case of Lab LSC member but not Lab personnel is unusual and will be handled by hand.
    * When membership in the new group should begin. 
    * If they are joining another group. If they indicate they are, then indicate the same information for the next group they are joining.
  * _Other information_ A text box is provided so that the transferee can provide any other information they feel is relevant.
3. _**Who is notified of a pending transfer?**_ 
  * If the transferee is leaving a group, the admins of the group they are leaving are notified of:
    * the name of the person who is leaving their group and the end date the transferee specified
  * If the transferee is joining a group simple MOU group, the admins of the MOU group they are joining are notified of:
    * the name of the person who is petitioning to join their group, the start date they specified, and the link to the petition approval page.
  * If the transferee is petitioning to join a federated MOU group, the institutional admins get the same notification as the MOU group admins
  * If the trasnferee is petitioning to join the LIGO Lab group, all admins are also informed whether the transferee indicated they wish to be an LSC member
  * In every case, the "other information" specified by the transferee in the text box is included
  * The transferee is also sent an email summarizing all the values (institutions being left and/or joined, dates, etc) they specified in the petition.
4. _**What information is shown on the transfer approval form?**_ 
  * The name of the transferee
  * The list of current and pending group memberships for the transferee
  * For each current group membership, the position, work contribution (WC) values, and end date
  * For each pending group membership, the start date 
  * The contents of the "other information" text box from the petition
  * A button to send an emai to initiate a "reconciliation" process (see below)
6. _**What is editable during approval?**_ Petitions to join a group (but not to leave a group) must be approved by MOU group or institutional admins for the group being joined. During approval, the admins MAY alter:
  * the start date of the petition
  * if it is a petition to join LIGO Lab, the approver may alter whether the transferee joins as an LSC member
The admins MUST further specify:
  * the position (affiliation) of the person within their group
  * the WC values for the person withing their group
  * the end date for person within their group _(we should not continue to allow "never" as an end date, see below)_
7. _**What validation is done before approval is finalized?**_ Total WC numbers for each date until the specified end date of the new membership are checked to ensure they do not exceed the maximum. These totals include the values for each current membership and each pending membership. If a pending membership has not been finalized, the WC numbers are set to 0 for that membership. 
8. _**What happens if validation fails?**_ The approver is shown the following information:
  * the approver is shown the date range for which the requested WC numbers exceeded the maximum and the largest amount by which the maximum WC was exceeded.
9. _**What happens if the approver initiates a reconciliation process?**_ When the reconciliation process button is pressed, the approver is shown the contents of an email that will be sent (see below) along with a text box in which they may write a message to the other recipients of the email with, for instance, a request to change end dates or WC values of existing memberships for the transferee. The contents of the email include:
  * the name of the transferee
  * the institutions in which they have current memberships along with the end dates and WC numbers for those memberships
  * the institutions in which they have pending memberships along with the start dates, end dates and approved WC numbers
  * the contents of the text box message from the approver who initiated the reconciliation process
When the "submit" button is pushed, the email with the specified contents is sent to the MOU Group and, if applicable, Institutional admins for all existing and pending memberships for the transferee, as well as to the Spokesperson.
10. _**What happens if validation succeeds?**_ The transfer into the group of the approver is finalized, and email is sent to the transferee, the MOU and, if applicable, institutional admins of any groups to which the transferee has current or pending memberships, and to the LSC spokesperson with the following information:
  * the name of the transferee
  * the list of current groups to which the transferee belongs with their end dates
  * the list of pending groups to which the transferee has been approved with their start and end dates
 
