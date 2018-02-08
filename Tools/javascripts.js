/**
 * 
 */
var arrow = [
             [ 2, 0 ],
             [ -10, -4 ],
             [ -10, 4]
         ];
var canvas,ctx;
var pad=0;

function drawFilledPolygon(shape) {
    ctx.beginPath();
    ctx.moveTo(shape[0][0],shape[0][1]);

    for(p in shape)
        if (p > 0) ctx.lineTo(shape[p][0],shape[p][1]);

    ctx.lineTo(shape[0][0],shape[0][1]);
    ctx.fill();
};

function translateShape(shape,x,y) {
    var rv = [];
    for(p in shape)
        rv.push([ shape[p][0] + x, shape[p][1] + y ]);
    return rv;
};

function rotateShape(shape,ang) {
    var rv = [];
    for(p in shape)
        rv.push(rotatePoint(ang,shape[p][0],shape[p][1]));
    return rv;
};

function rotatePoint(ang,x,y) {
    return [
        (x * Math.cos(ang)) - (y * Math.sin(ang)),
        (x * Math.sin(ang)) + (y * Math.cos(ang))
    ];
};

function drawLineArrow(x1,y1,x2,y2) {
    ctx.beginPath();
    ctx.moveTo(x1,y1);
    ctx.lineTo(x2,y2);
    ctx.stroke();
    var ang = Math.atan2(y2-y1,x2-x1);
    drawFilledPolygon(translateShape(rotateShape(arrow,ang),x2,y2));
};

function initArrows_Proces(naam,kleur,pad) {
	canvas = document.getElementById(naam);
    ctx = canvas.getContext('2d');
	if (kleur.substring(0, 5)=="Groen"){
    	ctx.strokeStyle="green";
        ctx.fillStyle="green";    		
	}
	else {
		if (kleur.substring(0, 5)=="Grijs"){
			ctx.strokeStyle="gray";
		    ctx.fillStyle="gray";
		}
	}
    drawLineArrow(100,0,100,45);
    return pad;
}
function initArrows_Desic(naam,pad,kleur) {
    canvas = document.getElementById(naam);
    ctx = canvas.getContext('2d');
    if (kleur.substring(0, 5)=="Groen"){
		ctx.strokeStyle="green";
		ctx.fillStyle="green";
    }
	else{
		if (kleur.substring(0, 5)=="Grijs"){
		ctx.strokeStyle="gray";
		ctx.fillStyle="gray";    	
		}
    }
	drawLineArrow(100,30,100,65);
    if (kleur.substring(5, 10)=="Groen"){
    	ctx.strokeStyle="green";
        ctx.fillStyle="green";
    }
    else{
    	if (kleur.substring(5, 10)=="Grijs"){
    		ctx.strokeStyle="gray";
    		ctx.fillStyle="gray";
    	}
    }
    drawLineArrow(100,30,300,65);
    return pad;
}
function initArrows_Split(naam,pad,join,kleur) {
    canvas = document.getElementById(naam);
    ctx = canvas.getContext('2d');
    if (kleur.substring(0, 5)=="Groen"){
		ctx.strokeStyle="green";
		ctx.fillStyle="green";
    }
	else{
		if (kleur.substring(0, 5)=="Grijs"){
		ctx.strokeStyle="gray";
		ctx.fillStyle="gray";    	
		}
    }
	drawLineArrow(100,0,100,65);
    switch (kleur.substring(5, 10))	
    {
    case "Groen":
    	ctx.strokeStyle="green";
        ctx.fillStyle="green";
        break;
    case "Grijs":
		ctx.strokeStyle="gray";
		ctx.fillStyle="gray";
    	break;
    default:
    	ctx.strokeStyle="black";
		ctx.fillStyle="black";
    }
    if(join==0){
    	end_x=300;
    	pad=0;
    }
    else{
    	end_x=100;
    }
    drawLineArrow(300,0,end_x,65);
    return pad;
};

function initArrows_Empty(naam,positie,locatie,kleur) {
    canvas = document.getElementById(naam);
    ctx = canvas.getContext('2d');
    switch (kleur)
    {
    case "Grijs":
    	ctx.strokeStyle="gray";
    	ctx.fillStyle="gray";
    	break;	
    case "Groen":
    	ctx.strokeStyle="green";
    	ctx.fillStyle="green";
    	break;	
    }
	ctx.font = "10px Arial";
	ctx.fillText(locatie,10,10);
	switch (positie)
	{
	case 1 :x_pos=97;
			break;
	case 2 :x_pos=68;
			break;
	case 0 :x_pos=98;
			break;
	}
	drawLineArrow(x_pos,0,x_pos,65);
	return pad;
};