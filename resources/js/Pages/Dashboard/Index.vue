<template>
  <div>
    <div class="content m-1">
      <Breadcrumbs :breadcrumbs="breadcrumbs" />
      <div class="grid grid-cols-2 gap-2 md:grid-cols-3 md:gap-4 lg:gap-8 mt-4">
        <div class="relative cursor-pointer transition transform ease-in-out hover:scale-105 hover:shadow-lg"
             v-for="school in schools" :key="school.id">
          <Card @click="visit(school)"
                class="bg-blue-200 h-32 lg:h-40 flex justify-center items-center">
            <p class="text-gray-700 absolute top-1 left-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor">
                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                <path
                  d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
              </svg>
            </p>
            <p class="text-center text-sm py-4 lg:text-base lg:py-4 xl:py-8">{{ school.name }}</p>
          </Card>
          <p
            v-if="($page.props.authUserRole==='ADMIN' || $page.props.authUserRole === 'SUPER_ADMIN')
            && school.un_approved_reports_count > 0"
            class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 flex justify-center items-center text-white text-sm">
            {{ school.un_approved_reports_count }}
          </p>
        </div>
        <div v-if="clockedIn && clockedIn.clock_out == null"
             @click="showClockinModal = true"
             class="relative cursor-pointer transition transform ease-in-out hover:scale-105 hover:shadow-lg">
          <Card class="bg-green-200 h-32 lg:h-40 flex justify-center items-center">
            <p class="text-gray-700 absolute top-1 left-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </p>
            <div class="text-center">
              <p>Clocked in at</p>
              <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-semibold uppercase">{{ clockedIn.clock_in }}</p>
            </div>
          </Card>
        </div>
      </div>
    </div>
    <ClockInModal v-if="showClockinModal"
                  v-model="showClockinModal"
                  :site-coordinates="siteCoordinates"
                  :clocked-in="clockedIn"
    />
  </div>
</template>

<script>
import Layout from "@/Shared/Layout";
import PageHeading from "@/Components/PageHeading";
import {Link} from "@inertiajs/inertia-vue3";
import Breadcrumbs from "@/Components/Breadcrumbs";
import Card from "../../Components/Card";
import ClockInModal from "../../Components/Forms/ClockInModal";

export default {
  name: "Dashboard",
  layout: Layout,
  components: {
    ClockInModal,
    Card,
    Breadcrumbs
  },
  props: {
    schools: {
      type: Object,
      required: true
    },
    siteCoordinates: {
      type: Object,
      default: null
    },
    clockedIn: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      breadcrumbs: [],
      showClockinModal: false
    };
  },
  methods: {
    visit(school) {
      this.$inertia.visit(route('dashboard.schools.show', school.id))
    },

  },
  watch: {}
  ,
  computed: {}
  ,
  mounted() {
    if (this.$page.props.authUserRole === 'TEACHER' && this.siteCoordinates != null && this.clockedIn == null) {
      this.showClockinModal = true;
    }
  }
}
;
</script>

