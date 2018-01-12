<template>
    <nav id="sidebar" class="bs-docs-sidebar" :style="{top : this.sidebarTop +'px'}">
        <ul class="nav nav-stacked">
            <li v-for="value in relationTypes" :class="{active : activeId == value.id}">
                <a :id="'navlink-'+value.id" :href="'#'+value.id" :click="scrollSpy">{{value.name}}</a>
            </li>
        </ul>
        <a href="#" class="back-to-top">Back to top</a>
    </nav>
</template>

<script>
    export default {

        props: ["relationTypes"],

        data: function () {
            return {
                activeId: null,
                sections: [],
                sidebarTop: 0,
                sidebarSideScroll: false,

                // boolean telling if the events are bound to a eventlistener
                binded: false,

                // function to add or remove from eventlistener
                events: {
                    resize: {
                        offsetTop: null,
                        scrollSpy: null,
                    },
                    scroll: {
                        scrollSpy: null,
                    }
                }
            }
        },

        mounted() {
            console.log('Sidebar mounted');

            this.events.resize.offsetTop = _.throttle(this.updateOffsetTop, 200);
            this.events.resize.scrollSpy = _.throttle(this.scrollSpy, 200);
            this.events.scroll.scrollSpy = _.throttle(this.scrollSpy, 100);

            this.manageBindByClientWidth();
            window.addEventListener('resize', this.manageBindByClientWidth);
        },

        destroyed() {
            this.unbind();
            window.removeEventListener('resize', this.manageBindByClientWidth);
        },

        methods: {
            bind() {
                this.updateOffsetTop();
                this.scrollSpy();

                window.addEventListener('resize', this.events.resize.offsetTop);
                window.addEventListener('resize', this.events.resize.scrollSpy);
                window.addEventListener('scroll', this.events.scroll.scrollSpy);
            },

            unbind() {
                window.removeEventListener('resize', this.events.resize.offsetTop);
                window.removeEventListener('resize', this.events.resize.scrollSpy);
                window.removeEventListener('scroll', this.events.scroll.scrollSpy);
            },

            manageBindByClientWidth() {
                if (document.body.clientWidth < 768) {
                    if (this.binded) {
                        console.log("unbinding");
                        this.binded = false;
                        this.unbind();
                    }
                } else if (!this.binded) {
                    console.log("binding");
                    this.bind();
                    this.binded = true;
                }
            },

            updateOffsetTop() {
                this.sections = _.map(this.relationTypes, function (value) {
                    let element = document.getElementById(value.id);
                    let sidebarElement = document.getElementById("navlink-" + value.id);
                    return {
                        offsetTop: element.offsetTop + element.offsetParent.offsetTop,
                        sidebarOffsetTop: sidebarElement.offsetParent.offsetTop,
                        id: value.id
                    }
                });


                let sidebar = $("#sidebar");
                // affix system
                $(window).off('.affix');
                sidebar.removeData('bs.affix').removeClass('affix affix-top affix-bottom');
                sidebar.affix({
                    offset: {
                        top: sidebar[0].parentElement.offsetTop,
                    }
                });

                this.sidebarSideScroll = sidebar.height() > document.body.clientHeight
            },

            scrollSpy: function () {
                let scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;

                let active = _.findLast(this.sections, element => element.offsetTop <= scrollPosition);

                if (active !== undefined) {
                    this.activeId = active.id;

                    let sidebarOffsetHeight = document.getElementById('sidebar').offsetHeight;

                    if (this.sidebarSideScroll) {
                        let proportion = (active.sidebarOffsetTop - (document.body.clientHeight / 2)) /
                            (sidebarOffsetHeight - (document.body.clientHeight / 2));
                        let top = (document.body.clientHeight - sidebarOffsetHeight ) * proportion;

                        this.sidebarTop = top < 0 ? top - (100 * proportion) : 0;
                    } else {
                        this.sidebarTop = 0;
                    }

                } else {
                    this.activeId = null;
                }

            }
        },
    }
</script>
<style>
    /* sidebar */
    .bs-docs-sidebar {
        padding-left: 20px;
        margin-top: 20px;
        margin-bottom: 20px;
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

    /* back to top*/
    .back-to-top {
        display: block;
        padding: 4px 10px;
        margin-top: 10px;
        margin-left: 10px;
        font-size: 12px;
        font-weight: 500;
        color: #999;
    }
</style>