document.addEventListener("DOMContentLoaded", function() {
    const departmentSelect = document.getElementById('department');
    const employeeSelect = document.getElementById('employee');

    fetch('fetch-department.php') 
        .then(response => response.json())
        .then(data => {
            departmentSelect.innerHTML = "<option value=''>Select Department</option>";
            data.forEach(department => {
                const option = document.createElement('option');
                option.value = department.id; 
                option.textContent = department.department; 
                departmentSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching departments:', error));
    departmentSelect.addEventListener('change', function() {
        const departmentId = this.value;
        employeeSelect.innerHTML = "<option value=''>Select Employee</option>"; 
        
        if (departmentId) {
            fetch(`fetch-employee.php?departmentId=${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id; 
                        option.textContent = `${employee.fname} ${employee.lname}`;
                        employeeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching employees:', error));
        }
    });
});
