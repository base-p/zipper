$(document).ready(function(){
        var classArray = [];
        $('*').each(function(){if(this.className){classArray.push(this.className)}});
        console.log(classArray);
        var idArray = [];
        $('*').each(function(){if($(this).attr('id')){idArray.push($(this).attr('id'))}});
        console.log(idArray);
});