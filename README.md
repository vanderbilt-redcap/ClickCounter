
**ClickCounter REDCap External Module Documentation**

This REDCap external module was created to count how many times people click configured links.

*Step 1 - Configuring Links and Counter Fields*
Determine which links you would like to count clicks for. Add each link to the ClickCounter's configuration by going to the External Modules -> Manage page and clicking 'Configure' next to the ClickCounter module. Each link can be paired with a counter field in the configuration.
![ClickCounter module configuration](/docs/picture_1.PNG)

*Step 2 - Collecting User Links*
Once the links have been added to the configuration, save the configuration modal and visit the 'ClickCounter Links' page under "External Modules" in the project's sidebar. This page shows each configured link (called a "target link") and an associated user link. If specified in the configuration, this is also where the errors table appears to show administrators how many of which types of error have occurred while counting clicks.
![ClickCounter module configuration](/docs/picture_2.PNG)

*Step 3 - Distributing User Links*
The user links generated are designed to be used in places where REDCap accepts data piping (e.g., the Online Designer and Alerts & Notifications interfaces). If you want to send user links to users directly, you should manually replace the URL parameter `rid=[record_id]` to be `rid=<actual record ID for intended recipient>`.

Example:
 http://localhost/redcap/redcap_v12.2.5/ExternalModules/?prefix=ClickCounter&page=link&pid=24&NOAUTH&target_link=1&rid=84

I've replaced `rid=[record_id]` in the user link with `rid=84`. This will ensure that when the link is clicked, the module will increment the counter field by 1 for that record.

*Step 4 - Reviewing Click Counts*
Since counter field values are stored in the records themselves, there are many ways to review click counts.
1. Review the record via the Data Entry interface. This is as simple as opening a record, navigating to the form containing the counter field, and reading the number in the counter field's value box.
2. Create a report to review counter fields for many records at once in REDCap.
3. Export the project/record data and review counter field values with external tools like R, Excel, or other data analysis tools.
