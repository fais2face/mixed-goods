/**
* e.g.
* <div class="animate__animated" data-animate="animate__fadeInDown">Some Content</div>
*/

const inViewport = (entries, observer) => {
    entries.forEach(entry => {
            let animateEff = entry.target.dataset.animate;
            if (entry.intersectionRatio > 0) {
                if(animateEff){
                    entry.target.classList.add(animateEff);
                }
            }
            // else {
            //     entry.target.classList.remove(animateEff);
            // }
        },
        {threshold: [0.5]}
    );
};

const Obs = new IntersectionObserver(inViewport);
const obsOptions = {
    root: null,
    threshold: 0,
    rootMargin: '0 0 -50px 0'
}; 

const ELs_inViewport = document.querySelectorAll('[data-animate]');
ELs_inViewport.forEach(element => {
    Obs.observe(element, obsOptions);
});
