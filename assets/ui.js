document.addEventListener('click',e=>{const t=e.target.closest('.btn,.nav-link,.qc .card');if(!t)return;
 const r=t.getBoundingClientRect(),s=Math.max(r.width,r.height),d=document.createElement('span');d.className='rip';
 d.style.cssText=`width:${s}px;height:${s}px;left:${e.clientX-r.left-s/2}px;top:${e.clientY-r.top-s/2}px`;t.appendChild(d);setTimeout(()=>d.remove(),600);});
document.querySelectorAll('.navbar .nav-link').forEach(a=>{const u=new URL(a.href,location.href);
 if(u.pathname===location.pathname&&u.search===location.search&&!u.hash&&!a.classList.contains('btn'))a.classList.add('active');});
const io=new IntersectionObserver(es=>es.forEach(x=>{if(x.isIntersecting){x.target.classList.add('on');io.unobserve(x.target)}}),{threshold:.1});
document.querySelectorAll('.card,#map').forEach(el=>{el.classList.add('rv');io.observe(el)});

/* Slider foto latar beranda: panah, titik, geser di HP, otomatis ganti tiap 6 detik */
(()=>{const h=document.querySelector('.hs');if(!h)return;const S=[...h.querySelectorAll('.hs-slide')],D=[...h.querySelectorAll('.hs-dot')];if(S.length<2)return;
 let i=0,t;const go=n=>{S[i].classList.remove('on');D[i]&&D[i].classList.remove('on');i=(n+S.length)%S.length;S[i].classList.add('on');D[i]&&D[i].classList.add('on')},
 stop=()=>clearInterval(t),play=()=>{stop();if(!matchMedia('(prefers-reduced-motion:reduce)').matches)t=setInterval(()=>go(i+1),6000)};
 h.querySelector('.hs-prev').onclick=()=>{go(i-1);play()};h.querySelector('.hs-next').onclick=()=>{go(i+1);play()};
 D.forEach((d,k)=>d.onclick=()=>{go(k);play()});h.addEventListener('mouseenter',stop);h.addEventListener('mouseleave',play);
 let x0=0;h.addEventListener('touchstart',e=>{x0=e.touches[0].clientX;stop()},{passive:true});
 h.addEventListener('touchend',e=>{const d=e.changedTouches[0].clientX-x0;if(Math.abs(d)>50)go(i+(d<0?1:-1));play()});play();})();
