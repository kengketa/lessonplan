<template>
  <div class="h-screen flex overflow-hidden bg-gray-100">
    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog
        as="div"
        static
        class="fixed inset-0 flex z-40 "
        @close="sidebarOpen = false"
        :open="sidebarOpen"
      >
        <TransitionChild
          as="template"
          enter="transition-opacity ease-linear duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="transition-opacity ease-linear duration-300"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75" />
        </TransitionChild>
        <TransitionChild
          as="template"
          enter="transition ease-in-out duration-300 transform"
          enter-from="-translate-x-full"
          enter-to="translate-x-0"
          leave="transition ease-in-out duration-300 transform"
          leave-from="translate-x-0"
          leave-to="-translate-x-full"
        >
          <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-blue-900">
            <div class="flex-shrink-0 flex items-center px-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="white">
                <path
                  d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
              </svg>
              <span class="text-gray-100 font-bold ml-3 text-xl">Lesson Plan</span>
            </div>
            <div class="mt-5 flex-1 h-0 overflow-y-auto">
              <nav class="px-2 space-y-1">
                <div v-for="item in navigation" :key="item.name">
                  <div
                    v-if="item.type === 'group'"
                    class="block p-2 text-white mt-2"
                  >
                    <h3>{{ item.name }}</h3>
                  </div>
                  <a
                    v-else
                    :href="item.href"
                    :class="[
                      isUrl(item.routeGroup)
                        ? 'bg-gray-900 text-white'
                        : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                      'group flex items-center px-2 py-2 text-base font-medium rounded-md',
                    ]"
                  >
                    <component
                      :is="item.icon"
                      :class="[
                        isUrl(item.routeGroup)
                          ? 'text-gray-300'
                          : 'text-gray-400 group-hover:text-gray-300',
                        'mr-4 h-6 w-6',
                      ]"
                      aria-hidden="true"
                    />
                    {{ item.name }}
                  </a>
                </div>
              </nav>
            </div>
          </div>
        </TransitionChild>
        <div class="flex-shrink-0 w-14" aria-hidden="true">
          <!-- Dummy element to force sidebar to shrink to fit close icon -->
        </div>
      </Dialog>
    </TransitionRoot>
    <!-- Static sidebar for desktop -->
    <div class="hidden lg:flex lg:flex-shrink-0">
      <div class="flex flex-col w-64">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex flex-col h-0 flex-1">
          <div class="flex items-center h-16 flex-shrink-0 px-4 bg-blue-900">
            <!-- <img class="h-8 w-auto" src="/images/shell.svg" alt="Shell logo" /> -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="white">
              <path
                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
            </svg>
            <span class="text-gray-100 font-bold ml-3 text-lg">Lesson Plan</span>
          </div>
          <div class="flex-1 flex flex-col overflow-y-auto">
            <nav class="flex-1 px-2 py-4 bg-blue-900 space-y-1">
              <div v-for="item in navigation" :key="item.name">
                <div
                  v-if="item.type === 'group'"
                  class="block p-2 text-white mt-2"
                >
                  <h3>{{ item.name }}</h3>
                </div>
                <Link
                  v-else
                  :href="item.href"
                  :class="[
                    isUrl(item.routeGroup)
                      ? 'bg-gray-900 text-white'
                      : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                    'group flex items-center px-2 py-2 text-sm font-medium rounded-md',
                  ]"
                >
                  <component
                    :is="item.icon"
                    :class="[
                      isUrl(item.routeGroup)
                        ? 'text-gray-300'
                        : 'text-gray-400 group-hover:text-gray-300',
                      'mr-3 h-6 w-6',
                    ]"
                    aria-hidden="true"
                  />
                  {{ item.name }}
                </Link>
              </div>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
      <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
        <button
          class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden"
          @click="sidebarOpen = true"
        >
          <span class="sr-only">Open sidebar</span>
          <MenuAlt2Icon class="h-6 w-6" aria-hidden="true" />
        </button>
        <div class="flex-1 px-4 flex justify-end">
          <div class="ml-4 flex items-center md:ml-6">
            <!-- Profile dropdown -->
            <Menu as="div" class="ml-3 relative flex justify-end">
              <div>
                <MenuButton
                  class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <span class="sr-only">Open user menu</span>

                  <button
                    v-if="$page.props.jetstream.managesProfilePhotos"
                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                  >
                    <img
                      class="h-8 w-8 rounded-full object-cover"
                      :src="$page.props.user.profile_photo_url"
                      :alt="$page.props.user.name"
                    />
                  </button>
                  <span v-else class="inline-flex rounded-md">
                    <button
                      type="button"
                      class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition"
                    >
                      {{ $page.props.user.name }}

                      <svg
                        class="ml-2 -mr-0.5 h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        />
                      </svg>
                    </button>
                  </span>
                </MenuButton>
              </div>
              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems
                  class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                >
                  <MenuItem
                    v-for="item in userNavigation"
                    :key="item.name"
                    v-slot="{ active }"
                  >
                    <Link
                      :href="item.href"
                      :class="[
                        active ? 'bg-gray-100' : '',
                        'block px-4 py-2 text-sm text-gray-700',
                      ]"
                    >{{ item.name }}
                    </Link
                    >
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <Link
                      href="#"
                      :class="[
                        active ? 'bg-gray-100' : '',
                        'block px-4 py-2 text-sm text-gray-700',
                      ]"
                      @click.prevent="logout"
                    >
                      Sign out
                    </Link>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>
      </div>

      <main class="flex-1 relative overflow-y-auto focus:outline-none">
        <div class="py-6">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Replace with your content -->
            <FlashMessage></FlashMessage>
            <slot />
            <!-- /End replace -->
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import {ref, computed, onMounted} from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {
  Dialog,
  DialogOverlay,
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import {
  BellIcon,
  CalendarIcon,
  ChartBarIcon,
  DatabaseIcon,
  FolderIcon,
  HomeIcon,
  MenuAlt2Icon,
  UsersIcon,
  LockClosedIcon,
  XIcon,
  ChipIcon,
  FireIcon,
  QrcodeIcon,
  AcademicCapIcon,
  ClockIcon,
  DocumentTextIcon,
} from "@heroicons/vue/outline";
import {Link} from "@inertiajs/inertia-vue3";
import {SearchIcon} from "@heroicons/vue/solid";
import {Inertia} from "@inertiajs/inertia";

const userNavigation = [
  {name: "Settings", href: route("profile.show")},
  // { name: 'Settings', href: '#' },
  // { name: 'Sign out', href: '#' },
];

const navs = {
  dashboard: {
    name: "Dashboard",
    href: route("dashboard"),
    icon: HomeIcon,
    routeGroup: "dashboard",
  },
  users: {
    name: "Users",
    href: route("dashboard.users.index"),
    icon: UsersIcon,
    routeGroup: "dashboard.users.*",
  },
  schools: {
    name: "Schools",
    href: route("dashboard.schools.index"),
    icon: AcademicCapIcon,
    routeGroup: "dashboard.schools.*",
  },
  clockIns: {
    name: "Clock-In",
    href: route("dashboard.clock_ins.index"),
    icon: ClockIcon,
    routeGroup: "dashboard.clock_ins.*",
  },
  timeSheets: {
    name: "My Timesheet",
    href: route("dashboard.my_time_sheet"),
    icon: ClockIcon,
    routeGroup: "dashboard.my_time_sheet",
  },
  meetings: {
    name: "Meetings",
    href: route("dashboard.meetings.index"),
    icon: DocumentTextIcon,
    routeGroup: "dashboard.meetings.*",
  },

  //roles: {
  //   name: "Roles",
  //   href: route("roles.index"),
  //   icon: LockClosedIcon ,
  //   routeGroup: "roles.*",
  // },
};
export default {
  name: "Layout",
  components: {
    Dialog,
    DialogOverlay,
    FlashMessage,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
    BellIcon,
    MenuAlt2Icon,
    SearchIcon,
    LockClosedIcon,
    XIcon,
    ChipIcon,
    FireIcon,
    QrcodeIcon,
    Link,
    DocumentTextIcon
  },
  data() {
    return {sidebarOpen: false, userNavigation: userNavigation};
  },
  methods: {
    logout() {
      Inertia.post(route("logout"));
    },
    isUrl(...urls) {
      return urls.some((url) => route().current(url));
    },
  },
  computed: {
    navigation() {
      let nav = [];
      const user = this.$page.props.authUser;
      const roles = this.$page.props.roleEnum;
      if (user.roles[0].id == roles.USER) {
        nav.push(navs.dashboard);
      } else if (user.roles[0].id == roles.TEACHER) {
        nav.push(navs.dashboard);
        nav.push(navs.timeSheets);
        nav.push(navs.meetings);
      } else if (user.roles[0].id == roles.ADMIN) {
        nav.push(navs.dashboard);
        nav.push(navs.users);
        //nav.push(navs.schools);
        nav.push(navs.clockIns);
        nav.push(navs.meetings);
      } else if (user.roles[0].id == roles.SUPER_ADMIN) {
        nav.push(navs.dashboard);
        nav.push(navs.users);
        //nav.push(navs.schools);
        nav.push(navs.clockIns);
        nav.push(navs.meetings);
      } else {
        //push nothing
      }
      return nav;
    },
  },
};
</script>
