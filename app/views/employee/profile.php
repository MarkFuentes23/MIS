<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select2 Example</title>
  <!-- Include jQuery -->

  <!-- Include Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Include Select2 JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>

  <h2>Select2 Example with Pre-selected Options</h2>

  <select class="js-example-basic-multiple" name="states[]" multiple="multiple" style="width: 300px;">
    <option value="AL" selected>Alabama</option>
    <option value="CA" selected>California</option>
    <option value="FL">Florida</option>
    <option value="NY">New York</option>
    <option value="TX">Texas</option>
    <option value="WY">Wyoming</option>
  </select>

  <script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
  </script>

</body>
</html>
