<template>
    <div class="container">
        <h2>{{ msg }}<img class="logo" src="/image/hublogo.png"/></h2>
        <h3>Current status of HubID API</h3>
        <span class="last-updated-stamp">Refreshed {{test_refreshed}} minutes ago</span>
        <div class="row">
            <div class="col-md-offset-2 col-md-10 ">
                <table v-if="results !== ''" class="table">
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Time</th>
                        <th>Tests</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in results" :key='item.id'>
                        <td>
                                <span v-if="item.status === 'pass'" class="glyphicon glyphicon-ok color-success"
                                      aria-hidden="true"></span>
                            <span v-else class="glyphicon glyphicon-remove
                                 color-failed" aria-hidden="true"></span>
                            {{item.category.slice(0, -4)}}
                        </td>
                        <td>{{item.time+'ms'}}</td>
                        <td>{{item.tests}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h3>System Metrics</h3>
            <ul class="nav nav-pills navbar-right first">
                <li data-period="day" class="active" @click.prevent="period('day')"><a href="#">Day</a></li>
                <li data-period="week" @click.prevent="period('week')"><a href="#">Week</a></li>
                <li data-period="month" @click.prevent="period('month')"><a href="#">Month</a></li>
            </ul>
            <hubculture-chart
                    :data='hub'
                    :dates="dates_hub"
                    :options="{responsive: false, maintainAspectRatio: false}"
                    :width="300"
                    :height="80">
            </hubculture-chart>
            <hubculture-admin-chart
                    :data='hubadmin'
                    :dates="dates_hubadmin"
                    :options="{responsive: false, maintainAspectRatio: false}"
                    :width="300"
                    :height="80">
            </hubculture-admin-chart>
            <ven-chart
                    :data="venvc"
                    :dates="dates_venvc"
                    :options="{responsive: false, maintainAspectRatio: false}"
                    :width="300"
                    :height="80">
            </ven-chart>
            <div class="col-md-8">
                <div class="incidents-list">
                    <h3>Past Incidents</h3>
                    <div class="status-day" v-for="(value, key, index) in dates">
                        <div class="large-date">{{key}}</div>
                        <div class="incident-container" v-if="value.length === 0">
                            <div class="incident-title">No incidents reported</div>
                        </div>
                        <div v-else class="incident-container" v-for="(value_child, key_child, index_child) in value">
                            <div class="incident-title"> {{value_child.incident}} in {{value_child.site.name}}</div>
                            <div class="incident-text">{{(value_child.incident === 0)?"Investigating":"Resolved"}}</div>
                            <small>{{value_child.created_at}}</small>
                        </div>
                    </div>
                </div>
            </div>
            <div>
            <footer class="col-md-12 custom-footer">
                &copy; Powered by CubeX
            </footer>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import HubcultureChart from '../components/HubcultureChart'
    import HubcultureAdminChart from '../components/HubcultureAdminChart'
    import VenChart from '../components/VenChart'

    export default {
        components: {HubcultureChart, HubcultureAdminChart, VenChart},
        data() {
            return {
                msg: 'Welcome to Unit Test App',
                results: [],
                incidents: [],
                dates: [],
                dates_hub: [],
                hub: [],
                dates_hubadmin: [],
                hubadmin: [],
                dates_venvc: [],
                venvc: [],
                test_refreshed: ''
            }
        },
        mounted() {

            this.fillCharts('day');
            this.fillResults();

        },
        methods: {
            period(period) {
                $('[data-period]').removeClass('active');
                $('[data-period="' + period + '"]').addClass('active');
                this.fillCharts(period);
            },
            fillCharts(period) {
                axios({
                    method: 'get',
                    baseURL: 'charts/' + period,
                }).then(({data}) => {
                    this.dates_hub = data.hub_dates;
                    this.hub = data.hub;
                    this.dates_hubadmin = data.hubadmin_dates;
                    this.hubadmin = data.hubadmin;
                    this.dates_venvc = data.venvc_dates;
                    this.venvc = data.venvc;

                });
            },
            fillResults() {
                axios({
                    method: 'get',
                    baseURL: 'test'
                }).then(({data}) => {
                    this.incidents = data.incidents;
                    this.results = data.results;
                    this.dates = data.dates;
                    this.test_refreshed = data.test_refreshed;
                });
            }
        }
    }
</script>
<style>
    .color-success {
        color: #2caeef;
    }

    .color-failed {
        color: #a94442;
    }

    .first {
        margin-right: 0;
    }

    .status-day:before {
        position: absolute;
        content: "";
        width: 2px;
        height: 100%;
        background-color: #EAEAEA;
        left: -22px;
        top: 5px;
    }

    .large-date:after {
        position: absolute;
        content: "";
        left: -27px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #ccc;
    }

    .status-day {
        position: relative;
    }

    .incident-container {
        padding-bottom: 10px;
    }

    .large-date {
        font-weight: 600 !important;
        font-size: 16px;
        color: #31373D;
    }
    .custom-footer{
        padding-top: .75rem;
        margin: 70px 0;
        border-top: 1px solid #ddd;
        overflow: hidden;
    }
    small,.last-updated-stamp{
        color: #949494 !important;
    }
    .logo{
        height: 50px;
    }
</style>

