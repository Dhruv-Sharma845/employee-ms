function showAddEmployeeForm() {
  document.getElementById("add_employee_form").style.display = "block";
}
function hideAddEmployeeForm() {
  document.getElementById("add_employee_form").style.display = "none";
}
$("#add_employee_form").submit(function(event) {
  event.preventDefault();
  var form = $(this);
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
