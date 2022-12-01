# LIGO MOU Transfer COmanage Registry Plugin

Transfers are complicated by a number of factors: 
- There are three main types of groups: 
  1. Simple MOU groups which are comprised of a single institution
  2. The LIGO Laboratory federated MOU group, which is comprised of LLO, LHO, CIT Laboratory and MIT Laboratory groups
  3. Other federated MOU groups which are comprised of multiple institutions under a single MOU
- Persons may transfer between institutions within the same federated group
- The LIGO Lab federated group has three statuses for a person: Lab MOU, Lab Personnel, Both
- There are two types of transfers processes: those resulting in a person belonging to a single group and those resulting in a joint appointment
- Transfers require coordination between the groups being left and the groups being joined
- Transfers change effort (WC) numbers but must not violate effort number policy

To understand how these different factors affect the transfer process, we consider the following questions:

## _**Who can initiate a transfer?**_
In all cases the transferee initiates the transfer. 

## _**What information is supplied to initiate the transfer?**_ 
The information is split into three categories:
  * _Memberships that are ending_ - The transferee is given a list of institutions to which they currently belong. They indicate (with, say, checkboxes) which, if any, institutions they will be leaving. For each institution they they are leaving, they must specify an end date. 
  * _Memberships that are starting_ - The transferee is asked if they will be joining one or more new institutions. If they answer affirmatively, they must specify:
    * Which institution they are leaving (preferably from a list of insitutions that includes all LIGO institutions except: 
     - those of which they are already member or 
     - those into which they are already transferring or  
    * When membership at the new institution should begin. 
    * If they are joining another institution. If they indicate they are, then indicate the same information for the next institution they are joining.
  * _Other information_ A text box is provided so that the transferee can provide any other information they feel is relevant.
## _**Who is notified of a pending transfer?**_ 
  * If the transferee is leaving an institution, the admins of the institution they are leaving are notified of:
    * the name of the person who is leaving
    * the end date the transferee specified
  * If the transferee is joining an institution, the admins of the institution they are joining are notified of:
    * the name of the person who is petitioning to join their institution
    * the start date they specified 
    * the "other information" specified by the transferee in the text box is included
    * the link to the petition approval page.
  * The transferee is also sent an email summarizing all the values (institutions being left and/or joined, dates, etc) they specified in the petition.
## _**What information is shown on the transfer approval form?**_ 
  * The name of the transferee
  * The list of current and pending institutional memberships for the transferee
  * For each current institutional membership, the position, work contribution (WC) values, and end date
  * For each pending institutional membership, the start date 
  * The contents of the "other information" text box from the petition
  * A button to send an emai to initiate a "reconciliation" process (see below)
## _**What is editable during approval?**_ 
Petitions to join an institution (but not to leave an institution) must be approved by the institutional admins for the institution being joined. During approval, the admins MAY alter:
  * the start date of the petition
The admins MUST further specify:
  * the position (affiliation) of the person within their institution
  * the WC values for the person withing their institution (providing they can do so without violating the LSC bylaws)
  * the end date for person within their institution _(we should not continue to allow "never" as an end date, see below)_
## _**What validation is done before approval is finalized?**_ 
Total WC numbers for each date until the specified end date of the new membership are checked to ensure they do not exceed the maximum. These totals include the values for each current membership and each pending membership. If a pending membership has not been finalized, the WC numbers are set to 0 for that membership. 
## _**What happens if validation fails?**_ 
The approver is shown the following information:
  * the approver is shown the date range for which the requested WC numbers exceeded the maximum (note that only positive WC numbers are allowed)
  * the largest amount by which the maximum WC was exceeded
## _**What happens if the approver initiates a reconciliation process?**_ 
When the reconciliation process button is pressed, the approver is shown the contents of an email that will be sent (see below) along with a text box in which they may write a message to the other recipients of the email with, for instance, a request to change end dates or WC values of existing memberships for the transferee. The contents of the email include:
  * the name of the transferee
  * the institutions in which they have current memberships along with the end dates and WC numbers for those memberships
  * the institutions in which they have pending memberships along with the start dates, end dates and approved WC numbers
  * the contents of the text box message from the approver who initiated the reconciliation process

When the "submit" button is pushed, the email with the specified contents is sent to the institutional admins for all existing and pending memberships for the transferee, as well as to the Spokesperson.d
## _**What happens if validation succeeds?**_ 
The transfer into the group of the approver is finalized, and email is sent to the transferee and institutional admins of any groups to which the transferee has current or pending memberships, and to the LSC spokesperson with the following information:
  * the name of the transferee
  * the list of current groups to which the transferee belongs with their end dates
  * the list of pending groups to which the transferee has been approved with their start and end dates
 ## _**What happens if a transferee trys to transfer to an MOU in which that have an existing membership?**_
 A person should not be acting in more than one role at a time. Therefore, if a transferee attempts to transfer into an MOU they already have a membership in, the petition should be marked as duplicate unless the existing membership(s) are status deleted, expired, or denied.
