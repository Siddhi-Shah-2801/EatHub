function addAmount(id){
    var amountBox = document.getElementById(id);
    console.log(amountBox);
    var amount = amountBox.value;
    if(amount<99){
        amountBox.value++;
    }
}

function submitAmount(id){
    var amountBox = document.getElementById(id);
    var amount = amountBox.value;
    if(amount>1){
        amountBox.value--;
    }
}