# HEART System User Manual

---

## Table of Contents

1. [Product Name](#product-name)
2. [Intended Use](#intended-use)
3. [Functions and Features](#functions-and-features)
4. [Technical Specifications](#technical-specifications)
5. [Installation Instructions](#installation-instructions)
6. [Description of How to Use/Operate the Product](#description-of-how-to-useoperrate-the-product)
   - [6.1 Authentication](#61-authentication)
   - [6.2 Dashboard Navigation](#62-dashboard-navigation)
   - [6.3 Beneficiary Management](#63-beneficiary-management)
   - [6.4 Event Management](#64-event-management)
   - [6.5 Attendance Tracking](#65-attendance-tracking)
   - [6.6 Service Records](#66-service-records)
   - [6.7 Staff Activity Logs](#67-staff-activity-logs)
   - [6.8 User Management](#68-user-management-admin-only)
   - [6.9 Report Generation](#69-report-generation)
7. [Troubleshooting Section](#troubleshooting-section)
8. [Maintenance Information](#maintenance-information)
9. [Glossary](#glossary)

---

## Product Name

**HEART System**  
*Health Education And Records Tracking System*

A comprehensive web-based platform designed for health organizations and outreach programs to manage beneficiaries, organize events, track attendance, record health services, and generate comprehensive reports for informed decision-making.

---

## Intended Use

This user manual is designed to guide end-users, administrators, and health workers through the complete functionality of the HEART System. It provides:

- **For Administrators**: Complete system control, user management, activity monitoring, and advanced reporting capabilities
- **For Health Workers**: Day-to-day operational tasks including beneficiary registration, event organization, attendance marking, and service record documentation
- **For All Users**: Step-by-step instructions, best practices, and troubleshooting guidance for common tasks

The document should be referenced whenever users need clarification on system features, encounter issues, or require detailed operational procedures.

---

## Functions and Features

### 1. **Beneficiary Management**
   - Register new beneficiaries with demographic information
   - Search and filter beneficiary records
   - Update beneficiary information
   - View complete beneficiary profile history

### 2. **Event Management**
   - Create and schedule health outreach events
   - Set event details (name, type, location, date, description, status)
   - Track event participants
   - Manage event status (Upcoming, Completed, Cancelled)

### 3. **Attendance Tracking**
   - Mark beneficiary attendance at events
   - Record time in/time out for participants
   - Search attendance records by beneficiary or event
   - Generate attendance reports

### 4. **Health Service Records**
   - Document medical services provided during events
   - Record diagnoses, treatments, and medicines distributed
   - Link services to specific beneficiaries and events
   - Filter records by service type, date, or provider

### 5. **Staff Activity Logging**
   - Automatic logging of all staff actions (create, update, delete)
   - Monitor system changes and user activities
   - Search activity logs by staff member or module
   - Filter logs by activity type

### 6. **User Management** (Admin Only)
   - Create new staff accounts (Admin, Worker)
   - Update user information
   - Manage user roles and permissions
   - Deactivate/activate user accounts

### 7. **Report Generation**
   - Generate Beneficiary Demographics Reports
   - Create Event Outreach Summaries
   - Export Attendance Monitoring Reports
   - Produce Health Service Records Reports
   - Export reports in CSV or PDF format
   - Filter reports by date range and parameters

### 8. **Role-Based Access Control**
   - **Admin Role**: Full system access, user management, advanced reporting
   - **Worker Role**: Operational tasks, data entry, basic reporting

---

## Technical Specifications

### Minimum System Requirements

| Specification | Requirement |
|---|---|
| **Operating System** | Windows 7 or later, macOS 10.12+, or Linux (Ubuntu 18.04+) |
| **Processor** | Intel Core i3 or equivalent (2.0 GHz or faster) |
| **RAM** | 4 GB |
| **Storage** | 10 GB available disk space |
| **Network** | Stable internet connection (2 Mbps or higher recommended) |

### Recommended Specifications

| Specification | Recommendation |
|---|---|
| **Operating System** | Windows 10/11, macOS 11+, or Linux (Ubuntu 20.04+) |
| **Processor** | Intel Core i5 or equivalent (2.5 GHz or faster) |
| **RAM** | 8 GB or more |
| **Storage** | 20 GB SSD (for faster performance) |
| **Network** | Stable internet connection (10 Mbps or higher) |

### Supported Browsers

- Google Chrome (version 90+)
- Mozilla Firefox (version 88+)
- Microsoft Edge (version 90+)
- Safari (version 14+)

### Server Requirements (For Administrators)

- **PHP**: 8.1 or higher
- **Database**: MySQL 8.0 or PostgreSQL 12+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Node.js**: 14.0+ (for build processes)

---

## Installation Instructions

### For End Users (Client-Side)

1. **Access the System**
   - Open your web browser (Chrome, Firefox, Edge, or Safari)
   - Navigate to the URL provided by your administrator: `http://<system-ip-address>:<port>`
   - The system will load the login page

2. **No Installation Required**
   - The HEART System is web-based and requires no software installation
   - Simply use your username and password to log in

### For System Administrators (Server-Side)

1. **Prerequisites**
   - Ensure PHP 8.1+, MySQL, and Composer are installed
   - Verify network connectivity and firewall settings

2. **Installation Steps**
   ```bash
   # Clone the repository
   git clone <repository-url> /path/to/heart-system
   cd /path/to/heart-system

   # Install dependencies
   composer install
   npm install
   npm run build

   # Create environment file
   cp .env.example .env

   # Generate application key
   php artisan key:generate

   # Run database migrations
   php artisan migrate

   # Seed initial roles and users
   php artisan db:seed

   # Start the development server
   php artisan serve
   ```

3. **Access the System**
   - The system will be available at `http://localhost:8000` (default)
   - Log in with default admin credentials provided by your IT team

---

## Description of How to Use/Operate the Product

### 6.1 Authentication

#### Logging In
1. Navigate to the HEART System login page
2. Enter your **Username** in the first field
3. Enter your **Password** in the second field
4. Click the **Login** button
5. You will be redirected to your dashboard

#### First-Time Login
- Your administrator will provide temporary login credentials
- Upon first login, you may be prompted to change your password
- Follow the on-screen instructions to update your password

#### Logging Out
- Click the **Logout** button in the sidebar
- You will be redirected to the login page
- Your session will be terminated

#### Password Recovery
- If you forget your password, contact your administrator
- Do not share your password with anyone

---

### 6.2 Dashboard Navigation

#### Main Dashboard
Upon logging in, you will see:

1. **Sidebar Menu** (Left side)
   - Dashboard link
   - Module navigation (Beneficiaries, Events, Attendance, Service Records)
   - Administration links (Staff Activities, User Management, Reports)
   - Logout button

2. **Top Navigation Bar**
   - System name and logo
   - Quick access to key features
   - User profile menu (if available)

3. **Main Content Area**
   - Key statistics and summary cards
   - Quick action buttons
   - Recent activity overview

#### Module Access
- Click on any module name in the sidebar to access that section
- Each module opens with a list view of records
- Use search and filter options to narrow your view

---

### 6.3 Beneficiary Management

#### Viewing Beneficiaries
1. Click **Beneficiaries** in the sidebar
2. The system displays a list of all registered beneficiaries
3. Use the search box to find specific beneficiaries by name or contact info
4. View beneficiary details by clicking on any record

#### Adding a New Beneficiary
1. Navigate to **Beneficiaries** module
2. Click the **Add Beneficiary** or **Create** button
3. Fill in the following information:
   - **First Name** (required)
   - **Middle Name** (optional)
   - **Last Name** (required)
   - **Email** (required, must be unique)
   - **Birth Date** (required)
   - **Age** (auto-calculated or manual entry)
   - **Gender** (Male, Female, Other)
   - **Address** (optional)
   - **Contact Number** (optional)
   - **Guardian Name** (optional)
4. Click **Save** or **Create Beneficiary**
5. A success message will confirm the beneficiary has been registered

#### Updating Beneficiary Information
1. Navigate to the beneficiary's profile
2. Click the **Edit** button
3. Modify the necessary fields
4. Click **Save Changes** or **Update**
5. The system will confirm the update

#### Searching Beneficiaries
1. In the Beneficiaries list, locate the search box
2. Enter the beneficiary's name, email, or contact number
3. Press **Enter** or click the search button
4. Results filter automatically

---

### 6.4 Event Management

#### Viewing Events
1. Click **Events** in the sidebar
2. The system displays a list of all events
3. View event status: Upcoming, Completed, or Cancelled
4. Click on any event to view detailed information

#### Creating a New Event
1. Navigate to **Events** module
2. Click the **Add Event** or **Create** button
3. Fill in the event details:
   - **Event Name** (required)
   - **Event Type** (e.g., Outreach, Health Seminar, Vaccination Drive)
   - **Event Date** (required)
   - **Location** (required)
   - **Description** (optional)
   - **Status** (Upcoming, Completed, Cancelled) - defaults to "Upcoming"
4. Click **Save** or **Create Event**
5. The event is now created and visible in the events list

#### Updating Event Information
1. Navigate to the event's detail page
2. Click the **Edit** button
3. Modify event details as needed
4. Click **Save Changes** or **Update**
5. Confirmation message appears

#### Deleting an Event
1. Navigate to the event you wish to delete
2. Click the **Delete** button
3. Confirm the deletion in the popup dialog
4. The event will be removed from the system

#### Viewing Event Participants
1. Open an event's detail page
2. View the list of registered beneficiaries for that event
3. See attendance status for each participant

---

### 6.5 Attendance Tracking

#### Marking Attendance
1. Navigate to **Attendance** module
2. Click **Mark Attendance** or **Add Attendance Record**
3. Select or search for the **Event**
4. Select or search for the **Beneficiary**
5. Set **Attendance Status**: Present or Absent
6. Enter **Time In** (optional)
7. Enter **Time Out** (optional)
8. Click **Save** or **Submit**
9. Record is saved with confirmation message

#### Viewing Attendance Records
1. Navigate to **Attendance** module
2. The system displays all attendance records
3. Use search filter to find records by:
   - Beneficiary name
   - Event name
   - Attendance status
4. View time in/time out details for each record

#### Editing Attendance
1. Find the attendance record you wish to modify
2. Click the **Edit** button
3. Update the attendance information
4. Click **Save Changes**
5. Confirmation message appears

#### Generating Attendance Reports
1. Navigate to **Reports** > **Attendance Report**
2. (Optional) Filter by:
   - Specific event
   - Date range
3. View attendance summary
4. Click **Export CSV** or **Export PDF** to download the report

---

### 6.6 Service Records

#### Creating a Service Record
1. Navigate to **Service Records** module
2. Click **Add Service Record** or **Create** button
3. Fill in the following details:
   - **Event** (required) - select from dropdown
   - **Beneficiary** (required) - search or select
   - **Service Type** (required) - e.g., Medical Consultation, Medication, Vaccination
   - **Diagnosis** (optional) - describe the condition
   - **Treatment Given** (required) - describe the treatment provided
   - **Service Date** (required) - date when service was provided
   - **Provided By** (required) - select the health worker
4. Click **Save** or **Create Record**
5. Confirmation message appears

#### Viewing Service Records
1. Navigate to **Service Records** module
2. View all service records in list format
3. Click on any record to view full details
4. Search records by:
   - Beneficiary name
   - Service type
   - Date range
   - Provider name

#### Updating Service Records
1. Find the record to modify
2. Click **Edit**
3. Update the necessary fields
4. Click **Save Changes**
5. System confirms the update

#### Filtering Service Records
1. In the Service Records list, use filter options
2. Filter by:
   - **Date From** and **Date To**
   - **Service Type** (dropdown)
   - **Event** (dropdown)
3. Click **Filter** to apply filters
4. Results update automatically

---

### 6.7 Staff Activity Logs

#### Viewing Activity Logs
1. Navigate to **Staff Activities** module
2. View all logged activities in chronological order
3. Each log shows:
   - Log timestamp (date and time)
   - Staff member name and ID
   - Activity performed
   - Module affected
   - Description of the activity

#### Searching Activity Logs
1. In the Staff Activities list, enter text in the search box
2. Search by:
   - Staff member name
   - Activity name
   - Description
3. Press **Enter** to search

#### Filtering by Module
1. Use the **Module** dropdown filter
2. Select from available modules:
   - Beneficiary
   - Outreach Event
   - Attendance
   - Service Record
   - Report
   - User Management
3. Click **Filter** to show logs for that module only
4. Click **Reset Filters** to see all logs

#### Interpreting Activity Logs
- **Created**: A new record was added to the system
- **Updated**: An existing record was modified
- **Deleted**: A record was removed
- **Viewed/Exported**: A report was generated or data was viewed

---

### 6.8 User Management (Admin Only)

#### Creating a New User Account
1. Navigate to **User Management** module (Admin only)
2. Click **Add User** or **Create** button
3. Enter the following information:
   - **First Name** (required)
   - **Last Name** (required)
   - **Email** (required, must be unique)
   - **Username** (required, must be unique)
   - **Password** (required) - set a secure temporary password
   - **Role** (required) - select Admin or Worker
   - **Status** (Active or Inactive) - defaults to Active
4. Click **Save** or **Create User**
5. Confirmation message appears with new user details

#### Viewing User Accounts
1. Navigate to **User Management** module
2. View list of all user accounts
3. See user information: name, email, username, role, status

#### Updating User Information
1. Find the user account to modify
2. Click **Edit**
3. Update fields:
   - Name, Email, Username
   - Role (can change from Worker to Admin or vice versa)
   - Status (Active/Inactive)
4. Click **Save Changes**
5. System confirms the update

#### Deactivating a User
1. Navigate to the user's record
2. Click **Edit**
3. Change **Status** from "Active" to "Inactive"
4. Click **Save Changes**
5. User will no longer be able to log in

#### Deleting a User
1. Find the user account to delete
2. Click **Delete** button
3. Confirm in the popup dialog
4. User account is removed from the system

---

### 6.9 Report Generation

#### Accessing Reports
1. Click **Reports** in the sidebar
2. View the Reports Dashboard with available report options
3. Each report type shows:
   - Report title and description
   - **View Report** button
   - **Export CSV** button (for data export)

#### Beneficiary Demographics Report
1. Navigate to **Reports** > **Beneficiary Demographics** or click "View Report"
2. (Optional) Apply filters:
   - **Registration Date From**: Select start date
   - **Registration Date To**: Select end date
3. Click **Filter** to apply
4. View beneficiary table with columns:
   - Full Name
   - Profile (Gender, Age)
   - Contact Info (Phone, Guardian)
   - Registration Date
5. Export options:
   - **Export CSV**: Downloads as spreadsheet
   - **Export PDF**: Downloads as formatted document

#### Event Outreach Summary Report
1. Navigate to **Reports** > **Event Outreach Summary**
2. (Optional) Filter by date range
3. View event details:
   - Event Name
   - Event Type
   - Event Date
   - Location
   - Status
   - Participants count
4. Export as CSV or PDF

#### Attendance Monitoring Report
1. Navigate to **Reports** > **Attendance Monitoring**
2. (Optional) Apply filters:
   - **Specific Event**: Select event from dropdown
   - **Date From/To**: Set date range
3. View attendance details:
   - Beneficiary name
   - Event name
   - Time in/Time out
   - Attendance status
4. Export the report

#### Health Service Records Report
1. Navigate to **Reports** > **Health Service Records**
2. (Optional) Filter by:
   - **Date range**
   - **Service Type**
   - **Event**
3. View service details:
   - Beneficiary name
   - Service type provided
   - Diagnosis (if recorded)
   - Treatment given
   - Service date
   - Provider name
4. Export as CSV or PDF

#### Exporting Reports
1. Open any report view
2. Click the **Export CSV** button for spreadsheet format
   - File downloads as `.csv` (opens in Excel, Google Sheets, etc.)
3. Click the **Export PDF** button for formatted document
   - File downloads as `.pdf` (opens in PDF viewer)
   - System displays "Generating PDF..." message during export

---

## Troubleshooting Section

### Common Issues and Solutions

#### Issue 1: Cannot Log In
**Problem**: Login fails with "Invalid credentials" message

**Causes**:
- Incorrect username or password
- Caps Lock is on
- Account is deactivated
- User account doesn't exist

**Solution**:
1. Verify that Caps Lock is OFF
2. Confirm username is correct (case-sensitive)
3. Verify password is correct (case-sensitive)
4. Contact your administrator to confirm your account exists and is active
5. If you forgot your password, ask administrator to reset it

---

#### Issue 2: Page Won't Load
**Problem**: Page shows blank or "404 Not Found" error

**Causes**:
- Network connection is poor
- System server is down
- Browser cache is corrupted
- Incorrect URL

**Solution**:
1. Check your internet connection
2. Verify the system URL is correct
3. Clear your browser cache:
   - Chrome: Press `Ctrl+Shift+Delete` → Select "All time" → Click "Clear data"
   - Firefox: Press `Ctrl+Shift+Delete` → Click "Clear Now"
   - Edge: Press `Ctrl+Shift+Delete` → Select time range → Click "Clear now"
4. Try a different browser
5. Contact your administrator if the issue persists

---

#### Issue 3: Changes Not Saving
**Problem**: After clicking Save, changes don't appear or page shows error

**Causes**:
- Network connection dropped
- Required field is blank
- Data validation failed
- Session expired

**Solution**:
1. Check all required fields (marked with *)
2. Verify data format (email format, date format, etc.)
3. Check internet connection
4. Log out and log back in (session refresh)
5. Try saving again
6. If error message appears, read and follow the specific guidance
7. Contact administrator if error persists

---

#### Issue 4: Search Returns No Results
**Problem**: Search for beneficiary or event returns no records

**Causes**:
- Record doesn't exist in system
- Spelling is incorrect
- Record is inactive or deleted
- Searching in wrong module

**Solution**:
1. Verify the record name is spelled correctly
2. Try searching by different field (name vs. email vs. contact)
3. Check that you're searching in the correct module
4. Try removing search filters and view all records
5. Ask administrator if record should exist

---

#### Issue 5: Report Won't Export
**Problem**: Export PDF or CSV button doesn't work

**Causes**:
- No data to export (no records match filters)
- Browser popup blocked export file
- Network timeout
- System file permission issue

**Solution**:
1. Verify there are records in the report to export
2. Check browser popup/download settings:
   - Allow pop-ups and downloads for the system URL
   - Check your Downloads folder for the file
3. Try exporting in different format (CSV instead of PDF)
4. Try a different browser
5. Contact administrator if issue continues

---

#### Issue 6: Forgot Your Password
**Problem**: Cannot remember or access your account password

**Causes**:
- Password forgotten
- Account locked due to failed attempts
- Password expired

**Solution**:
1. Contact your system administrator immediately
2. Provide your username and email
3. Administrator will reset your password
4. Use the temporary password provided to log in
5. Change your password upon first login

---

#### Issue 7: Permission Denied Error
**Problem**: "You do not have permission to access this page" message

**Causes**:
- User role doesn't have access to module
- User account permissions were removed
- Session permissions changed

**Solution**:
1. Verify your assigned role (Admin or Worker)
2. Check if the module is restricted to your role
3. Log out and log back in to refresh permissions
4. Contact administrator if you need additional access
5. Ask administrator to verify your role and permissions

---

#### Issue 8: Duplicate Record Created
**Problem**: Same beneficiary or event appears twice in system

**Causes**:
- Form submitted twice (double-click)
- Page reloaded during save
- Unique field wasn't actually unique

**Solution**:
1. Check if records are truly identical or just similar
2. If duplicate, ask administrator to delete one record
3. Be careful not to double-click buttons during save
4. Contact administrator if duplicates appear frequently

---

#### Issue 9: Data Appears Outdated
**Problem**: Changes made by other users don't appear immediately

**Causes**:
- Page not refreshed to show latest data
- Browser cache showing old data
- Automatic refresh interval hasn't passed

**Solution**:
1. Press **F5** or **Ctrl+R** to refresh the page
2. Clear browser cache (see Issue 2)
3. Wait a moment for automatic refresh (usually 30 seconds)
4. Navigate away and back to the module

---

#### Issue 10: Cannot Upload or Attach Files
**Problem**: File upload fails or attachment button doesn't work

**Causes**:
- File size exceeds limit
- File type not allowed
- Upload folder permissions issue

**Solution**:
1. Check file size (typical limit is 10MB)
2. Verify file format is acceptable (PDF, JPG, PNG, DOC, XLSX)
3. Compress large files before uploading
4. Try uploading smaller file to test
5. Contact administrator if problem continues

---

### General Troubleshooting Steps

If you encounter an issue not listed above:

1. **Restart Your Browser**
   - Close all browser windows completely
   - Reopen browser and log in again

2. **Try a Different Browser**
   - Issue might be browser-specific
   - Try Chrome, Firefox, Safari, or Edge

3. **Check Your Internet Connection**
   - Open a different website to verify connectivity
   - Restart your router if needed

4. **Clear Browser Cache**
   - Clear cached data, cookies, and history
   - Restart browser

5. **Log Out and Log In Again**
   - Session refresh often resolves issues
   - Logs you back in with fresh permissions

6. **Contact Your Administrator**
   - Document the issue (steps taken, error messages)
   - Provide screenshots if possible
   - Note the exact time the issue occurred

---

## Maintenance Information

### Regular Maintenance Tasks

#### For System Administrators

**Daily Tasks**:
- Monitor system performance and uptime
- Check for error logs and warnings
- Verify backup completion

**Weekly Tasks**:
- Review staff activity logs for anomalies
- Backup database and application files
- Check disk space availability

**Monthly Tasks**:
- Review user accounts and permissions
- Archive old activity logs if needed
- Update access reports
- Performance optimization review

**Quarterly Tasks**:
- Update server software and patches
- Security audit and penetration testing
- User training and refresher sessions
- System documentation review and updates

#### Software Updates

**Update Cycle**:
- Security patches: Apply as soon as available
- Minor updates: Review before applying
- Major updates: Plan maintenance window in advance

**Update Process**:
1. Notify all users of maintenance window
2. Backup all data
3. Take system offline
4. Apply updates in test environment first
5. Verify all functionality
6. Apply updates to production
7. Test all modules with real data
8. Notify users system is back online

### System Dependencies

**Third-Party Software**:
- Laravel Framework (PHP)
- MySQL Database
- Bootstrap 5 (Frontend UI)
- SweetAlert2 (User notifications)
- DomPDF (PDF generation)
- Maatwebsite Excel (CSV export)
- Mockery/PHPUnit (Testing framework)

**Subscription/License Requirements**:
- None at this time (all open-source)
- Domain name registration (if using custom domain)
- SSL Certificate (for HTTPS security)
- Web hosting or server costs

### Backup and Recovery

**Backup Strategy**:
- Daily automated database backups
- Weekly full system backups
- Monthly long-term archive backups

**Recovery Procedure**:
1. Contact IT administrator immediately
2. Identify backup point in time
3. Restore from most recent valid backup
4. Verify data integrity
5. Resume normal operations

### Support and Contact

**For System Issues**:
- Contact: IT Support Team
- Email: it-support@organization.com
- Phone: [Support phone number]
- Hours: [Support hours]

**For User Training**:
- Attend training sessions (monthly)
- Request individual training from administrator
- Consult this user manual
- Contact your direct supervisor

---

## Glossary

| Term | Definition |
|---|---|
| **Beneficiary** | An individual registered in the system who receives health services or participates in outreach events |
| **Event** | A scheduled health outreach activity or program organized by the organization |
| **Attendance** | Record of a beneficiary's participation in an event (Present/Absent status) |
| **Service Record** | Documentation of a health service or treatment provided to a beneficiary |
| **Staff Activity Log** | Automatic system record of all actions performed by staff members |
| **Module** | A major section or feature of the HEART System (e.g., Beneficiaries, Events) |
| **Role** | A set of permissions assigned to a user account (Admin or Worker) |
| **Admin** | User with full system access including user management and reporting |
| **Worker** | User with access to operational modules for data entry and basic reporting |
| **Dashboard** | The main page displayed after login showing system overview and statistics |
| **Filter** | Feature to narrow down results based on specific criteria |
| **Export** | Download data from the system in CSV or PDF format |
| **Session** | The period of time a user is logged into the system |
| **Credentials** | Username and password used for authentication |
| **CSV** | Comma-Separated Values format, a spreadsheet file format |
| **PDF** | Portable Document Format, a file format for documents and reports |
| **Server** | The computer that hosts and runs the HEART System |
| **Database** | The storage system that holds all system data |
| **Browser** | Software used to access the web-based HEART System (Chrome, Firefox, etc.) |
| **Cache** | Temporary storage of web data that can sometimes cause display issues |
| **Timestamp** | Date and time when an event or action occurred |
| **Query** | A search or filter request to retrieve specific data |
| **Backup** | A copy of system data for disaster recovery purposes |
| **Authentication** | The process of verifying a user's identity through login |
| **Authorization** | The process of determining what a user is allowed to do based on their role |
| **Outreach** | Health education or service programs conducted outside a health facility |
| **Diagnosis** | The identification of a medical condition |
| **Treatment** | Medical care or intervention provided to a patient |

---

## Document Control

| Item | Details |
|---|---|
| **Document Version** | 1.0 |
| **Date Created** | May 9, 2026 |
| **Last Updated** | May 9, 2026 |
| **Maintained By** | IT Support Team |
| **Next Review Date** | August 9, 2026 |

---

**End of User Manual**
