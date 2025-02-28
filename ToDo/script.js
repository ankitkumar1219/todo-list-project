
let input=document.getElementById('inputId');
let addbtn=document.getElementById('buttonId');
function displayDate() {
    const today = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = today.toLocaleDateString('en-US', options); // Format date to 'Month Day, Year'
    document.getElementById('date').textContent = date;
}
displayDate();
function addtask(){
    if(input.value==''){
        alert('Please enter task');
        return;
    }else{
        let list =document.getElementById('task-list');
         let newElem=document.createElement('li');
         newElem.innerHTML=`${input.value} <button class="delete-btn"><i class="fa-solid fa-trash"></i></button>`;
         let deleteBtn = newElem.querySelector('.delete-btn');
            deleteBtn.addEventListener('click', function () {
                newElem.remove(); // Remove the task from the list
            });
         input.value='';
         list.appendChild(newElem);
    }
}
