<template>
    <div>
        <div class="content m-1">
            <Breadcrumbs :breadcrumbs="breadcrumbs" :back="route('dashboard.grades.index')" />
            <PageHeading>
                {{ title }}
                <template #actions>
                    <inertia-link
                        class="button button-primary"
                        :href="route('dashboard.grades.create')"
                    >
                        <UserAddIcon class="h-5 w-5 mr-2" aria-hidden="true" />
                        Create Grade
                    </inertia-link>
                </template>
            </PageHeading>
            <div class="grid grid-cols-12 gap-2 content-center">
                <div class="col-span-12 md:col-span-5">
                    <TextInput
                        v-model="form.search"
                        :isShowLine="false"
                        placeholder="search.."
                    ></TextInput>
                </div>
                <div class="col-span-12 md:col-span-2 flex items-end justify-end">
                    <div>
                        <button
                            @click="clearForm"
                            class="button button-secondary button-small"
                        >
                            Clear
                        </button>
                    </div>
                </div>
            </div>
            <TableDisplayContainer>
                <template #header>
                    <TableTh v-for="(column,index) in columns" :key="index">
                        <div class="flex">
                            <span>{{ column }}</span>
                        </div>
                    </TableTh>
                    <TableTh>Actions</TableTh>
                </template>
                <template #body>
                    <tr
                        v-for="(item, itemIndex) in grades.data"
                        :key="item"
                        :class="itemIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
                    >
                        <TableTd>
														<inertia-link
															:href="route('dashboard.grades.show',item.id)"
															class="hover:underline"
														>
															{{ item.school_id }}
														</inertia-link>
													</TableTd>
													<TableTd>
														<inertia-link
															:href="route('dashboard.grades.show',item.id)"
															class="hover:underline"
														>
															{{ item.type }}
														</inertia-link>
													</TableTd>
													<TableTd>
														<inertia-link
															:href="route('dashboard.grades.show',item.id)"
															class="hover:underline"
														>
															{{ item.level }}
														</inertia-link>
													</TableTd>
													<TableTd>
														<inertia-link
															:href="route('dashboard.grades.show',item.id)"
															class="hover:underline"
														>
															{{ item.room_number }}
														</inertia-link>
													</TableTd>
													
                        <TableTd>
                            <inertia-link :href="route('dashboard.grades.edit', item.id)" class="link">
                                Edit
                            </inertia-link>
                        </TableTd>
                    </tr>
                </template>
                <template #pagination>
                    <Pagination :data="grades.meta.pagination"></Pagination>
                </template>
            </TableDisplayContainer>
        </div>
    </div>
</template>

<script>
    import Layout from "@/Shared/Layout";
    import Breadcrumbs from "@/Components/Breadcrumbs";
    import mapValues from "lodash/mapValues";
    import Pagination from "@/Components/Pagination";
    import PageHeading from "@/Components/PageHeading";
    import TableDisplayContainer from "@/Components/TableDisplayContainer";
    import TableTh from "@/Components/TableTh";
    import TableTd from "@/Components/TableTd";
    import TextInput from "@/Components/TextInput";
    import SelectInput from "@/Components/SelectInput";
    import { UserAddIcon } from "@heroicons/vue/solid";

    export default {
        name: "GradeIndex",
        layout: Layout,
        components: {
            Pagination,
            PageHeading,
            TableDisplayContainer,
            TableTh,
            TableTd,
            TextInput,
            SelectInput,
            Breadcrumbs,
            UserAddIcon,
        },
        props: {
            grades: Object,
            filters: Object,
            title: String,
        },
        data() {
            return {
                form: {
                    search: this.filters.search ?? "",
                },
                breadcrumbs: [{ name: 'Grades', href: "#" }],
                columns:['school_id', 'type', 'level', 'room_number', ],
            };
        },
        methods: {
            removeGrade: function (grade) {
                if (confirm("Are you sure to remove this grade?")) {
                    grade._method = "DELETE";
                    this.$inertia.post(route("dashboard.grades.destroy", grade), grade);
                }
            },
            clearForm: function () {
                this.form = mapValues(this.form, () => "");
            },
            debounceSearch() {
                clearTimeout(this.debounce);
                this.debounce = setTimeout(() => {
                    this.typing = null;
                    this.sendRequest();
                }, 500);
            },
            sendRequest() {
                this.$inertia.replace(this.route("dashboard.grades.index", this.form));
            },

        },
        watch: {
            form: {
                handler: function () {
                    this.debounceSearch();
                },
                deep: true,
            },
        },
        computed:{
        }
    };
</script>

