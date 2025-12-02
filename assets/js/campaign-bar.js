/* ---------------------------------------------------------
   WR Campaign Bar JS
   - Hover scale effect
   - Elementor Edit Mode marquee fix
---------------------------------------------------------- */

document.addEventListener("DOMContentLoaded", function () {

    /* ---------------------------------------
       Hover scaling effect
    --------------------------------------- */
    const bars = document.querySelectorAll(".wr-campaign-bar");

    bars.forEach(bar => {
        bar.addEventListener("mouseenter", () => {
            bar.style.transform = "scale(1.01)";
        });
        bar.addEventListener("mouseleave", () => {
            bar.style.transform = "scale(1)";
        });
    });


    /* ---------------------------------------
       Elementor editor marquee animation fix
       (Without this, animation may freeze)
    --------------------------------------- */
    if (typeof window.elementorFrontend !== "undefined") {

        const fixMarquee = () => {
            const marquees = document.querySelectorAll(".wr-marquee-inner");
            marquees.forEach(inner => {
                inner.style.animationPlayState = "running";
            });
        };

        // Run once after load
        setTimeout(fixMarquee, 300);

        // Run again when Elementor widgets are re-rendered
        document.addEventListener("elementor/frontend/init", fixMarquee);
    }

});
