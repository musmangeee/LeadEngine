import layoutHelpers from "@/layout/helpers.js";

export default function() {
    return {
        // Public url
        publicUrl: "/",

        // Layout helpers
        layoutHelpers,
        chartColor: {
            blue: "#00a5ee",
            green: "#7cd276",
            yellow: "#dcd400",
            orange: "#d59100",
            red: "#cb4547",
            grey: "#7f7f7f",
            black: "#2d2d2d"
        },

        genderOption: [
            { value: "Male", text: "Male" },
            { value: "Female", text: "Female" }
        ],

        // Check for RTL layout
        get isRTL() {
            return (
                document.documentElement.getAttribute("dir") === "rtl" ||
                document.body.getAttribute("dir") === "rtl"
            );
        },

        // Check if IE
        get isIEMode() {
            return typeof document["documentMode"] === "number";
        },

        // Check if IE10
        get isIE10Mode() {
            return this.isIEMode && document["documentMode"] === 10;
        },

        // Layout navbar color
        get layoutNavbarBg() {
            return "navbar-theme";
        },

        // Layout sidenav color
        get layoutSidenavBg() {
            return "sidenav-theme";
        },

        // Layout footer color
        get layoutFooterBg() {
            return "footer-theme";
        },

        // Animate scrollTop
        scrollTop(
            to,
            duration,
            element = document.scrollingElement || document.documentElement
        ) {
            if (element.scrollTop === to) return;
            const start = element.scrollTop;
            const change = to - start;
            const startDate = +new Date();

            // t = current time; b = start value; c = change in value; d = duration
            const easeInOutQuad = (t, b, c, d) => {
                t /= d / 2;
                if (t < 1) return (c / 2) * t * t + b;
                t--;
                return (-c / 2) * (t * (t - 2) - 1) + b;
            };

            const animateScroll = () => {
                const currentDate = +new Date();
                const currentTime = currentDate - startDate;
                element.scrollTop = parseInt(
                    easeInOutQuad(currentTime, start, change, duration)
                );
                if (currentTime < duration) {
                    requestAnimationFrame(animateScroll);
                } else {
                    element.scrollTop = to;
                }
            };

            animateScroll();
        }
    };
}
