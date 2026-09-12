

function qs(s, r){ return (r||document).querySelector(s); }
function slot(cls, label, src, alt){
  if(src) return '<img class="'+cls+'" src="'+src+'" alt="'+(alt||'')+'" loading="lazy">';
  return '<div class="'+cls+' ph"><span class="u-mono">'+label+'</span></div>';
}



function dotField(canvas, o){
  o = o || {};
  var gap = o.gap || 22, dot = o.dot || 2;
  var color = o.color || '#16161A';
  var maxA = o.alpha != null ? o.alpha : 0.5;
  var speed = o.speed || 1;
  var fadeMs = o.fadeMs != null ? o.fadeMs : 700;
  var cursor = o.cursor !== false;

  var ctx = canvas.getContext('2d');
  var w=0, h=0, dpr = Math.min(window.devicePixelRatio||1, 2);
  var mx=-9999, my=-9999, start = performance.now();
  var running = true, paused = false;

  function resize(){
    w = canvas.clientWidth; h = canvas.clientHeight;
    if(!w || !h) return;
    canvas.width = w*dpr; canvas.height = h*dpr;
    ctx.setTransform(dpr,0,0,dpr,0,0);
  }
  resize();
  window.addEventListener('resize', resize);

  if(cursor){
    window.addEventListener('mousemove', function(e){
      var r = canvas.getBoundingClientRect();
      mx = e.clientX - r.left; my = e.clientY - r.top;
    });
  }

  function frame(now){
    if(!running) return;
    requestAnimationFrame(frame);
    if(paused || !w || !h) return;

    var el = now - start;
    var fade = fadeMs ? Math.min(1, el/fadeMs) : 1;
    var t = el/1000*speed;

    ctx.clearRect(0,0,w,h);
    ctx.fillStyle = color;

    for(var y=gap; y<h; y+=gap){
      for(var x=gap; x<w; x+=gap){
        var v = Math.sin(x*0.011 + t*0.55)
              + Math.sin(y*0.013 - t*0.42)
              + Math.sin((x+y)*0.007 + t*0.30);
        var a = Math.pow((v/3 + 1)/2, 2.6);
        if(cursor){
          var dx=x-mx, dy=y-my, d=Math.sqrt(dx*dx+dy*dy);
          if(d < 240) a += (1 - d/240)*0.7;
        }
        a *= fade;
        if(a < 0.03) continue;
        ctx.globalAlpha = Math.min(a,1)*maxA;
        ctx.fillRect(x, y, dot, dot);
      }
    }
  }
  requestAnimationFrame(frame);
  return { stop:function(){running=false;}, pause:function(p){paused=p;}, resize:resize };
}

/* ------------------------------------------------------------
   SPLASH  (homepage only)
   ------------------------------------------------------------ */
(function(){
  var splash = qs('#splash');
  if(!splash) return;

  var ONCE_PER_SESSION = false;   // set true before launch
  var FADE_IN_MS = 700, HOLD_MS = 1900;

  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches){
    splash.classList.add('is-done'); return;
  }
  var seen = false;
  try{ seen = ONCE_PER_SESSION && sessionStorage.getItem('khoji_splash')==='1'; }catch(e){}
  if(seen){ splash.classList.add('is-done'); return; }

  var ink = getComputedStyle(document.documentElement).getPropertyValue('--splash-ink').trim();
  var field = dotField(qs('#splashCanvas'), { color:ink, fadeMs:FADE_IN_MS });
  requestAnimationFrame(function(){ splash.classList.add('is-in'); });

  var closed = false;
  function close(){
    if(closed) return;
    closed = true;
    splash.classList.add('is-done');
    try{ sessionStorage.setItem('khoji_splash','1'); }catch(e){}
    setTimeout(field.stop, 900);
  }
  setTimeout(close, FADE_IN_MS + HOLD_MS);
  ['click','wheel','touchstart','keydown'].forEach(function(ev){
    window.addEventListener(ev, close, {once:true, passive:true});
  });
})();


(function(){
  var header = qs('#header');
  if(!header) return;
  var ticking = false;


  function update(){
    header.classList.toggle('is-pill', window.scrollY > 70);
    ticking = false;
  }
  window.addEventListener('scroll', function(){
    if(!ticking){ requestAnimationFrame(update); ticking = true; }
  }, {passive:true});
  update();
})();


(function(){
  var cvs = qs('#featureLines'); if(!cvs) return;
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var ctx = cvs.getContext('2d');
  var w = 0, h = 0, dpr = Math.min(window.devicePixelRatio || 1, 2);
  var lines = [];
  var running = true, paused = true;

  function resize(){
    w = cvs.clientWidth; h = cvs.clientHeight;
    if(!w || !h) return;
    cvs.width = w * dpr; cvs.height = h * dpr;
    ctx.setTransform(dpr,0,0,dpr,0,0);

    // rebuild the line set for the new size
    lines = [];
    var count = Math.max(9, Math.round(w / 120));
    for(var i=0;i<count;i++){
      lines.push({
        x: Math.random() * w,
        speed: 6 + Math.random() * 40,     // px/sec drift, slow and calm
        len: h * (0.9 + Math.random()*0.5),
        tilt: -0.12,                        // gentle diagonal
        opacity: 0.05 + Math.random()*0.70
      });
    }
  }
  resize();
  window.addEventListener('resize', resize);

  var last = performance.now();
  function frame(now){
    if(!running) return;
    requestAnimationFrame(frame);
    var dt = Math.min(now - last, 50) / 1000;
    last = now;
    if(paused || !w || !h) return;

    ctx.clearRect(0,0,w,h);
    ctx.strokeStyle = '#f8f3f3';
    ctx.lineWidth = 1;

    lines.forEach(function(l){
      l.x += l.speed * dt;
      if(l.x - l.len*Math.abs(l.tilt) > w + 40) l.x = -40; // wrap around

      ctx.globalAlpha = l.opacity;
      ctx.beginPath();
      ctx.moveTo(l.x, 0);
      ctx.lineTo(l.x + l.len*l.tilt, l.len);
      ctx.stroke();
    });
  }
  requestAnimationFrame(frame);

  // only animate while the section is actually on screen
  if('IntersectionObserver' in window){
    new IntersectionObserver(function(es){
      es.forEach(function(e){ paused = !e.isIntersecting; });
    }, {threshold:0}).observe(cvs);
  } else { paused = false; }
})();

(function(){
  var bg = qs('.feature__bg'); if(!bg) return;
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var section = bg.closest('.feature');
  var ticking = false;

  function update(){
    var r = section.getBoundingClientRect();
    var vh = window.innerHeight;
    if(r.bottom > 0 && r.top < vh){
      var progress = (vh - r.top) / (vh + r.height); // 0 -> 1 across the section's time on screen
      var shift = (progress - 0.5) * 40; // px, capped and gentle
      bg.style.transform = 'translateY(' + shift.toFixed(1) + 'px) scale(1.08)';
    }
    ticking = false;
  }
  window.addEventListener('scroll', function(){
    if(!ticking){ requestAnimationFrame(update); ticking = true; }
  }, {passive:true});
  update();
})();

(function(){
  var wrap = qs('#searchWrap'); if(!wrap) return;
  var toggle = qs('#searchToggle');
  toggle.addEventListener('click', function(e){
    e.preventDefault();
    wrap.classList.toggle('is-open');
    if(wrap.classList.contains('is-open')){
      var input = wrap.querySelector('input');
      if(input) setTimeout(function(){ input.focus(); }, 50);
    }
  });
  document.addEventListener('click', function(e){
    if(!wrap.contains(e.target)) wrap.classList.remove('is-open');
  });
})();

/* ------------------------------------------------------------
   MOBILE MENU
   ------------------------------------------------------------ */
(function(){
  var menu = qs('#mmenu'); if(!menu) return;
  var b = qs('#burger'), c = qs('#mclose');
  if(b) b.addEventListener('click', function(){ menu.classList.add('is-open'); });
  if(c) c.addEventListener('click', function(){ menu.classList.remove('is-open'); });
  Array.prototype.forEach.call(menu.querySelectorAll('a'), function(a){
    a.addEventListener('click', function(){ menu.classList.remove('is-open'); });
  });
})();

/* ------------------------------------------------------------
   FOOTER DOT FIELD  — pauses when off screen
   ------------------------------------------------------------ */
(function(){
  var cvs = qs('#footCanvas'); if(!cvs) return;
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var field = dotField(cvs, { gap:26, color:'#FFFFFF', alpha:0.20, speed:0.45, fadeMs:0, cursor:false });
  field.pause(true);

  if('IntersectionObserver' in window){
    new IntersectionObserver(function(es){
      es.forEach(function(e){
        if(e.isIntersecting) field.resize();
        field.pause(!e.isIntersecting);
      });
    }, {threshold:0}).observe(cvs.parentElement);
  } else { field.resize(); field.pause(false); }
})();
