
var moduleLinks = document.querySelectorAll('.module-toggle');
Array.prototype.forEach.call(moduleLinks, function(el, i){
    el.onclick = function(event) {
        event.preventDefault();
        
        var listEle = document.getElementById(el.getAttribute('data-ListID'));
        listEle.classList.toggle('active');

        el.textContent = listEle.classList.contains('active') ? 'Hide' : 'Show';
    }
});