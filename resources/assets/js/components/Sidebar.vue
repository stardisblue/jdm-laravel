<template>
    <nav id="sidebar" class="bs-docs-sidebar" :style="{top : this.top +'px'}">
        <ul class="nav nav-stacked">
            <li v-for="value in relationTypes" :class="{active : activeId == value.id}">
                <a :href="'#'+value.id" :click="scrollSpy">{{value.name}}</a>
            </li>
        </ul>
    </nav>
</template>

<script>
    import * as _ from "lodash";

    export default {
        data() {
            return {
                active: null,
                activeId: null,
                sections: [],
                top: 0,
                parentOffset: null,
                sidebar: null,
            }
        },

        mounted() {
            console.log('Sidebar mounted');
            this.sidebar = $("#sidebar");

            window.addEventListener('load', this.updateOffsetTop);
            window.addEventListener('resize', _.throttle(this.updateOffsetTop, 100));
            window.addEventListener('load', this.scrollSpy);
            window.addEventListener('resize', _.throttle(this.scrollSpy, 100));
            window.addEventListener('scroll', _.throttle(this.scrollSpy, 50));
        },

        destroyed() {
            window.removeEventListener('load', this.updateOffsetTop);
            window.removeEventListener('resize', _.throttle(this.updateOffsetTop, 100));
            window.removeEventListener('load', this.scrollSpy);
            window.removeEventListener('resize', _.throttle(this.scrollSpy, 100));
            window.removeEventListener('scroll', _.throttle(this.scrollSpy, 50));
        },

        methods: {
            updateOffsetTop() {
                this.sections = _.map(this.relationTypes, function (value) {
                    let element = document.getElementById(value.id);
                    let sidebarElements = document.querySelectorAll("a[href='#" + value.id + "']");
                    return {
                        offsetTop: element.offsetTop + element.offsetParent.offsetTop,
                        sidebarOffsetTop: sidebarElements[0].offsetParent.offsetTop,
                        id: value.id
                    }
                });

                this.parentOffset = this.sidebar[0].parentElement.offsetTop;

                // affix system
                $(window).off('.affix');
                this.sidebar.removeData('bs.affix').removeClass('affix affix-top affix-bottom');
                this.sidebar.affix({
                    offset: {
                        top: this.parentOffset,
                    }
                });

            },

            scrollSpy() {
                console.log('salut');
                let scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;

                let active = null;

                _.forEach(this.sections, (element) => {
                    if (element.offsetTop <= scrollPosition) {
                        active = element;
                    } else {
                        return false;
                    }
                });

                this.active = active;
                if (active !== null) {
                    this.activeId = active.id;

                    let proportion = (active.sidebarOffsetTop - (document.body.clientHeight / 2)) /
                        (this.sidebar.height() - (document.body.clientHeight / 2));
                    let top = -((this.sidebar.height() - document.body.clientHeight + 50) * proportion);

                    this.top = top < 0 ? top : 0;

                }

            }
        },

        props: ["relationTypes"]
    }
</script>
<style>
    /* sidebar */
    .bs-docs-sidebar {
        padding-left: 20px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .bs-docs-sidebar.affix {
        position: fixed;
        top: 0;
    }

    /* all links */
    .bs-docs-sidebar .nav > li > a {
        color: #999;
        border-left: 2px solid transparent;
        padding: 4px 20px;
        font-size: 13px;
        font-weight: 400;
    }

    /* nested links */
    .bs-docs-sidebar .nav .nav > li > a {
        padding-top: 1px;
        padding-bottom: 1px;
        padding-left: 30px;
        font-size: 12px;
    }

    /* active & hover links */
    .bs-docs-sidebar .nav > .active > a,
    .bs-docs-sidebar .nav > li > a:hover,
    .bs-docs-sidebar .nav > li > a:focus {
        color: #563d7c;
        text-decoration: none;
        background-color: transparent;
        border-left-color: #563d7c;
    }

    /* all active links */
    .bs-docs-sidebar .nav > .active > a,
    .bs-docs-sidebar .nav > .active:hover > a,
    .bs-docs-sidebar .nav > .active:focus > a {
        font-weight: 700;
    }

    /* nested active links */
    .bs-docs-sidebar .nav .nav > .active > a,
    .bs-docs-sidebar .nav .nav > .active:hover > a,
    .bs-docs-sidebar .nav .nav > .active:focus > a {
        font-weight: 500;
    }

    /* hide inactive nested list */
    .bs-docs-sidebar .nav ul.nav {
        display: none;
    }

    /* show active nested list */
    .bs-docs-sidebar .nav > .active > ul.nav {
        display: block;
    }

</style>