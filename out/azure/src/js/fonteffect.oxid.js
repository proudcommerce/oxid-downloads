$(function(){
    $("h1.pageHead, .box h3, .box h2, .sectionHead").not(".lightHead").FontEffect({ 
            shadow :true,
            shadow :true,
            shadowColor :"#104d5c",
            shadowOffsetTop :0,
            shadowOffsetLeft :1,
            shadowBlur :1 
    });
    $(".lightHead").FontEffect({ 
            shadow :true,
            shadow :true,
            shadowColor :"#ffffff",
            shadowOffsetTop :2,
            shadowOffsetLeft :1,
            shadowBlur :1 
    });    
});