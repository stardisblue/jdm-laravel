<template>
    <nav id="sidebar" class="bs-docs-sidebar sticky-top" :style="{top : this.sidebarTop +'px'}">
        <div class="inner-addon left-addon">
            <i class="glyphicon glyphicon-search"></i>
            <input type="text" class="form-control" v-model="research" placeholder="Filter">
        </div>
        <ul class="nav nav-stacked">
            <li v-for="value in filteredRelationTypes" :class="{active : activeIndex === value.index}">
                <a :id="'navlink-'+ value.index" :href="'#rt'+value.index">{{value.name}}</a>
            </li>
        </ul>

        <a href="#" class="back-to-top">Back to top</a>
    </nav>

</template>

<script>
    export default {

        props: {
            relationTypes: {
                type: Array,
                required: true,
            },
            activeIndex: {
                type: Number,
                required: true,
            }
        },

        data: function () {
            return {
                sections: [],
                sidebarTop: 0,
                sidebarSideScroll: false,
                sidebarOffsetHeight: 0,
                // boolean telling if the events are bound to a eventlistener
                binded: false,

                // function to add or remove from eventlistener
                events: {
                    resize: {
                        offsetTop: null,
                    },
                },
                research: "",
                filteredRelationTypes: []
            }
        },

        watch: {
            activeIndex: function () {
                if (this.activeIndex === -1) {
                    return;
                }
                const active = _.nth(this.sections, this.activeIndex);
                if (this.sidebarSideScroll) {
                    const clientHeight = document.body.clientHeight - 50;
                    const proportion = (active.sidebarOffsetTop - (clientHeight / 2)) /
                        (this.sidebarOffsetHeight - (clientHeight / 2));
                    const top = ((clientHeight - this.sidebarOffsetHeight) * proportion);

                    this.sidebarTop = (top < 0 ? top - (100 * proportion) : 0) + 50;
                } else {
                    this.sidebarTop = 50;
                }
            },

            research: _.debounce(function () {
                this.filteredRelationTypes = _.filter(this.relationTypes, (value) => {
                    return _.includes(value.name, this.research);
                })
            }, 200),
        },

        methods: {
            bind() {
                this.updateOffsetTop();
                window.addEventListener('resize', this.events.resize.offsetTop);
            },

            unbind() {
                window.removeEventListener('resize', this.events.resize.offsetTop);
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
                this.sections = _.map(this.relationTypes, function (value, index) {
                    let sidebarElement = document.getElementById("navlink-" + index);
                    return {
                        sidebarOffsetTop: sidebarElement.offsetParent.offsetTop,
                    }
                });

                let sidebar = $("#sidebar");
                // affix system
                $(window).off('.affix');
                sidebar.removeData('bs.affix').removeClass('affix affix-top affix-bottom');
                sidebar.affix({
                    offset: {
                        top: sidebar[0].parentElement.offsetTop - 50,
                    }
                });

                this.sidebarOffsetHeight = document.getElementById('sidebar').offsetHeight;
                this.sidebarSideScroll = sidebar.height() > document.body.clientHeight
            },
        },

        created() {
            this.events.resize.offsetTop = _.throttle(this.updateOffsetTop, 200);
            window.addEventListener('resize', this.manageBindByClientWidth);
            this.filteredRelationTypes = this.relationTypes;
        },

        mounted() {
            //console.log('Sidebar mounted');
            this.manageBindByClientWidth();
        },

        destroyed() {
            this.unbind();
            window.removeEventListener('resize', this.manageBindByClientWidth);
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