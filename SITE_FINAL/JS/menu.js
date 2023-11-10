// Selecionando só uma aba
var menuItem = document.querySelectorAll('.item_menu')

function selectLink(){
    menuItem.forEach((item)=>
    item.classList.remove('ativo')
    )

    this.classList.add('ativo')
}

menuItem.forEach((item)=>
item.addEventListener('click', selectLink)
)

// expandir menu
var bntExp = document.querySelector('#btn_exp')
var menuVar = document.querySelector('.menu_lateral')

bntExp.addEventListener('click', function(){
    menuVar.classList.toggle('expandir')
})