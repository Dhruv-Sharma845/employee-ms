function showAddEmployeeForm() {
  document.getElementById("add_employee_form").style.display = "block";
}
function hideAddEmployeeForm() {
  document.getElementById("add_employee_form").style.display = "none";
}
$("#add_employee_form").submit(function(event) {
  event.preventDefault();
  var form = $(this);

  var errorMsg = "";

  var userFirstName = $("input[name=emp_firstName]").val();
  if (userFirstName == null || userFirstName.length > 50) {
    errorMsg += "FirstName is not in correct format";
  }
  var userLastName = $("input[name=emp_lastName]").val();
  if (userLastName == null || userLastName.length > 50) {
    errorMsg += "LastName is not in correct format";
  }
  var userPhone = $("input[name=emp_phone]").val();
  var IndNum = /^[0]?[789]\d{9}$/;

  if (userPhone == null || userPhone.length > 13 || !IndNum.test(userPhone)) {
    errorMsg += "Phone is not in correct format";
  }

  if (errorMsg !== "") {
    alert(errorMsg);
    return;
  }
  var emp = {
    firstName: $("input[name=emp_firstName]").val(),
    lastName: $("input[name=emp_lastName]").val(),
    phone: $("input[name=emp_phone]").val()
  };
  console.log(emp);
  $.ajax({
    url: "http://localhost:8000/employees/create",
    type: "post",
    data: JSON.stringify(emp),
    dataType: "json",
    encode: true,
    async: true,
    contentType: "application/json",
    success: function(response) {
      hideAddEmployeeForm();
      refreshEmployeesList(response);
    }
  });
});

function refreshEmployeesList(response) {
  $.ajax({
    url: "http://localhost:8000/employees/",
    type: "get",
    async: true
  }).done(function(data) {
    $("#employeesListDiv").html(data);
  });
}

function removeEmployee(empId) {
  console.log("Removing employee with id" + empId);
  $.ajax({
    url: "http://localhost:8000/employees/remove/" + empId,
    type: "delete",
    async: true
  }).done(function(response) {
    refreshEmployeesList(response);
  });
}

function showUpdateEmployeeForm(empId, empFirstName, empLastName, empPhone) {
  $("input[name=update_emp_id]").val(empId);
  $("input[name=update_emp_firstName]").val(empFirstName);
  $("input[name=update_emp_lastName]").val(empLastName);
  $("input[name=update_emp_phone]").val(empPhone);
  document.getElementById("update_employee_form").style.display = "block";
}

function hideUpdateEmployeeForm() {
  document.getElementById("update_employee_form").style.display = "none";
}

$("#update_employee_form").submit(function(event) {
  event.preventDefault();
  var form = $(this);

  var errorMsg = "";

  var userFirstName = $("input[name=update_emp_firstName]").val();
  if (userFirstName == null || userFirstName.length > 50) {
    errorMsg += "FirstName is not in correct format";
  }
  var userLastName = $("input[name=update_emp_lastName]").val();
  if (userLastName == null || userLastName.length > 50) {
    errorMsg += "LastName is not in correct format";
  }
  var userPhone = $("input[name=update_emp_phone]").val();
  var IndNum = /^[0]?[789]\d{9}$/;

  if (userPhone == null || userPhone.length > 13 || !IndNum.test(userPhone)) {
    errorMsg += "Phone is not in correct format";
  }

  if (errorMsg !== "") {
    alert(errorMsg);
    return;
  }
  var emp = {
    firstName: $("input[name=update_emp_firstName]").val(),
    lastName: $("input[name=update_emp_lastName]").val(),
    phone: $("input[name=update_emp_phone]").val()
  };
  var empId = $("input[name=update_emp_id]").val();
  console.log(emp);
  $.ajax({
    url: "http://localhost:8000/employees/update/" + empId,
    type: "post",
    data: JSON.stringify(emp),
    dataType: "json",
    encode: true,
    async: true,
    contentType: "application/json",
    success: function(response) {
      hideUpdateEmployeeForm();
      refreshEmployeesList(response);
    }
  });
});
