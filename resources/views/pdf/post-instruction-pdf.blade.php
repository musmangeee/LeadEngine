<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <style>
        body {
font-family: 'Helvetica'
}
    </style>
</head>
  <body class="mt-0 p-0">
    <div class="col-12 text-center mb-5">
        <img src="images/logo.png" class="mb-3">
        <h3>Posting Instructions for {{ $provider->name }}</h3>
    </div>

    <div class="border rounded">
        <div class="card-body">
            <h5 class="card-title">Protocol Details</h5>
            <!--<h6 class="card-subtitle mb-2 text-muted">This is the URL to post your XML</h6>-->
            <p class="card-text">
                <code>
                    Request Format: HTTPS POST <br />
                    Request Service: https://aimleadconnect.com/intake <br />
                    Response Format: XML 1.0 <br />
                    Response Encoding: UTF-8 <br />
                </code>
            </p>
        </div>
    </div>

    <div class="border rounded mt-3">
        <div class="card-body">
            <h5 class="card-title">Request</h5>
            <h6 class="card-subtitle mb-2 text-muted">Example of import XML</h6>
            <p class="card-text"><code><pre>{{ $post_data }}</pre></code></p>
        </div>
    </div>

    <h5 class="mt-5">XML Parameter Specifications</h5>
    <table class="table" style="font-size: 12px">
        <thead>
            <tr>
                <th>Field</th><th>Description</th><th>Type</th><th>Possible Value(s)</th><th>Required</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ProviderUUID</td><td>Provider UUID</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>PortfolioUUID</td><td>Portfolio UUID</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>REFURL</td><td>Referral URL</td><td>String</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>IPADDRESS</td><td>Referral IP Address</td><td>String</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>AFFID</td><td>Affiliate ID</td><td>String</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>SUBID</td><td>SubID</td><td>String</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>TEST</td><td>Test</td><td>String</td><td>{ true / false }</td><td>Optional</td>
            </tr>
            <tr>
                <td>REQUESTDAMOUNT</td><td>Requested Loan Amount</td><td>Numeric</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>SSN</td><td>Social Security Number</td><td>Numeric[9]</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>DOB</td><td>Date Of Birth</td><td>Date [mm-dd-yyyy]</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>FIRSTNAME</td><td>First Name</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>LASTNAME</td><td>Last Name</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>ADDRESS</td><td>Address</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>ADDRESS2</td><td>Address 2</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>CITY</td><td>City</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>STATE</td><td>Residence State</td><td>String[2]</td><td>2 Character US State Abbreviation</td><td>Yes</td>
            </tr>
            <tr>
                <td>ZIP</td><td>Zip Code</td><td>Numeric[5]</td><td>Valid US Zip Code</td><td>Yes</td>
            </tr>
            <tr>
                <td>PHONE</td><td>Phone Number</td><td>Numeric [10]</td><td>Valid US Phone Number, No delimiters</td><td>Yes</td>
            </tr>
            <tr>
                <td>DLSTATE</td><td>Drivers License Issuing State</td><td>String[2]</td><td>2 Character US State Abbreviation</td><td>Yes</td>
            </tr>
            <tr>
                <td>DLNUMBER</td><td>Drivers License Number</td><td>String[2]</td><td>Valid DL Number</td><td>Yes</td>
            </tr>
            <tr>
                <td>CONTACTTIME</td><td>Contact Time</td><td>String</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>ADDRESSMONTHS</td><td>Months at current residence</td><td>Numeric</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>ADDRESSYEARS</td><td>Years at current residence</td><td>Numeric</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>RENTOROWN</td><td>Specify whether the applicant owns or rents this residence</td><td>String</td><td>{ Rent / Own }</td><td>Yes</td>
            </tr>
            <tr>
                <td>ISMILITARY</td><td>Specify Military status of the applicant</td><td>String</td><td>{ Yes / No }</td><td>Yes</td>
            </tr>
            <tr>
                <td>ISCITIZEN</td><td>Specify Citizenship of the applicant</td><td>String</td><td>{ true / false }</td><td>Yes</td>
            </tr>
            <tr>
                <td>OTHEROFFERS</td><td>Other Offer</td><td>String</td><td>{ true / false }</td><td>Optional</td>
            </tr>
            <tr>
                <td>EMAIL</td><td>Email Address</td><td>String</td><td>Valid email address</td><td>Yes</td>
            </tr>
            <tr>
                <td>LANGUAGE</td><td>Language</td><td>String[2]</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>INCOMETYPE</td><td>Primary source of income</td><td>String</td><td>{ E = employed / D = disability / S = social security / U = unemployed / P = pension / O = other }</td><td>Yes</td>
            </tr>
            <tr>
                <td>PAYTYPE</td><td>Pay type</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>EMPMONTHS</td><td>Months at current job</td><td>Numeric</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>EMPYEARS</td><td>Years at current job</td><td>Numeric</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>EMPNAME</td><td>Current employer name</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>EMPPHONE</td><td>Work phone</td><td>Numeric [10]</td><td>Valid US Phone, No delimiters</td><td>Yes</td>
            </tr>
            <tr>
                <td>HIREDATE</td><td>Next pay date</td><td>Date [yyyy-mm-dd]</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>EMPTYPE</td><td>Employement type</td><td>String</td><td>{ 'F', 'P' }</td><td>Yes</td>
            </tr>
            <tr>
                <td>JOBTITLE</td><td>Occupation / Job Title</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>PAYFREQUENCY</td><td>How often is the applicant paid</td><td>String</td><td>{ W = weekly / B = bi-weekly / S = semi-monthly / M = monthly }</td><td>Yes</td>
            </tr>
            <tr>
                <td>NETMONTHLY</td><td>Net monthly income</td><td>Numeric</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>LASTPAYDATE</td><td>Last pay date</td><td>Date [yyyy-mm-dd]</td><td>No holiday</td><td>Optional</td>
            </tr>
            <tr>
                <td>NEXTPAYDATE</td><td>Next pay date</td><td>Date [yyyy-mm-dd]</td><td>No holiday</td><td>Yes</td>
            </tr>
            <tr>
                <td>SECONDPAYDATE</td><td>Second pay date</td><td>Date [yyyy-mm-dd]</td><td>No holiday</td><td>Optional</td>
            </tr>
            <tr>
                <td>BANKNAME</td><td>Bank name</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>ACCOUNTTYPE</td><td>Account Type</td><td>String[1]</td><td>{ C / S }</td><td>Yes</td>
            </tr>
            <tr>
                <td>ROUTINGNUMBER</td><td>Routing/ABA Number</td><td>Numeric[9]</td><td>Valid ABA/Routing Number</td><td>Yes</td>
            </tr>
            <tr>
                <td>ACCOUNTNUMBER</td><td>Account Number</td><td>Numeric[10]</td><td>Valid Account Number</td><td>Yes</td>
            </tr>
            <tr>
                <td>BANKMONTHS</td><td>Months at current bank</td><td>Numeric</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>BANKYEARS</td><td>Years at current bank</td><td>Numeric</td><td></td><td>Optional</td>
            </tr>
            <tr>
                <td>USERIPADDRESS</td><td>User Ip Address</td><td>String</td><td></td><td>Yes</td>
            </tr>
            <tr>
                <td>REFERENCES</td><td>References</td><td>String</td><td></td><td>Optional</td>
            </tr>
        </tbody>
    </table>

    <div class="border rounded mt-3">
        <div class="card-body">
            <h5 class="card-title">Accepted Response</h5>
            <p class="card-text"><code><pre>{{ $responses['accepted'] }}</pre></code></p>
        </div>
    </div>

    <div class="border rounded mt-3">
        <div class="card-body">
            <h5 class="card-title">Reject Response</h5>
            <p class="card-text"><code><pre>{{ $responses['rejected'] }}</pre></code></p>
        </div>
    </div>
  </body>
</html>
