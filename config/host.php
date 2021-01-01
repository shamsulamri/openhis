
<?php

return [
    'application_name'      => env('APPLICATION_NAME', 'OpenHIS'),
    'report_server'      => env('REPORT_SERVER', 'localhost'),
    'mrn_prefix'      => env('MRN_PREFIX', 'MRN'),
    'tax_number'      => env('GST_NUMBER', '123456'),
    'reservation_limit'      => env('RESERVATION_LIMIT', 5),
    'show_emergency'      => env('SHOW_EMERGENCY', 0),
    'label_admission'      => env('LABEL_ADMISSION', 'Admission'),
    'label_ward'      => env('LABEL_WARD', 'Ward'),
    'label_consultant'      => env('LABEL_CONSULTANT', 'Consultant'),
    'label_location'      => env('LABEL_LOCATION', 'Location'),
    'label_current_address'      => env('LABEL_CURRENT_ADDRESS', 'Current Address'),
    'label_permanent_address'      => env('LABEL_PERMANENT_ADDRESS', 'Permanent Address'),
    'label_description_placeholder'      => env('LABEL_DESCRIPTION_PLACEHOLDER', 'Queue number or other relevant information'),
    'label_description_label'      => env('LABEL_DESCRIPTION_LABEL', 'Description'),
    'multiple_bill'      => env('MULTIPLE_BILL', 0),
]

?>
