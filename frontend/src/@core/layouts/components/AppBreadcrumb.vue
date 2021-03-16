<template>
  <b-row
    v-if="$route.meta.breadcrumb || $route.meta.pageTitle"
    class="content-header"
  >

    <!-- Content Left -->
    <b-col
      class="content-header-left mb-1"
      cols="12"
      md="10"
    >
      <b-row class="breadcrumbs-top">
        <b-col cols="12">
          <h2 class="content-header-title float-left pr-1 mb-0">
            {{ $route.meta.pageTitle }}
          </h2>
          <div class="breadcrumb-wrapper">
            <b-breadcrumb>
              <b-breadcrumb-item to="/">
                <feather-icon
                  icon="HomeIcon"
                  size="16"
                  class="align-text-top"
                />
              </b-breadcrumb-item>
              <b-breadcrumb-item
                v-for="item in $route.meta.breadcrumb"
                :key="item.text"
                :active="item.active"
                :to="item.to"
              >
                {{ item.text }}
              </b-breadcrumb-item>
            </b-breadcrumb>
          </div>
        </b-col>
      </b-row>
    </b-col>

    <!-- Content Right -->
    <b-col
   
      class="content-header-right text-md-right d-md-block mb-1"
      md="2"
      cols="12"
      
    >
      <!-- <b-navbar-nav class="nav align-items-center" style="padding-top:0 !important;">
        <b-nav-item-dropdown
          right
          toggle-class="d-flex align-items-center dropdown-user-link"
          class="dropdown-user"
        >
          <template #button-content>
            <div class="d-sm-flex d-none user-nav"  >
              <p class="user-name font-weight-bolder mb-0">
                John Paul Alcabasa
              </p>
              <span class="user-status">Administrator</span>
            </div>
            <b-avatar
              size="40"
              variant="light-primary"
              badge
              :src="require('@/assets/images/avatars/default.jpg')"
              class="badge-minimal"
              badge-variant="success"
            />
          </template>

          <b-dropdown-item link-class="d-flex align-items-center">
            <feather-icon
              size="16"
              icon="UserIcon"
              class="mr-50"
            />
            <span>Profile</span>
          </b-dropdown-item>

          <b-dropdown-divider />

          <b-dropdown-item link-class="d-flex align-items-center" @click="logout">
            <feather-icon
              size="16"
              icon="LogOutIcon"
              class="mr-50"
            />
            <span>Logout</span>
          </b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav> -->
      <b-dropdown
        variant="danger"
        no-caret
        toggle-class="p-0"
        right
      >

        <template #button-content>
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="danger"
            class="btn-icon"
          >
            <feather-icon icon="MenuIcon" />
          </b-button>
        </template>

        <b-dropdown-item :to="{ name: 'apps-todo' }">
          <feather-icon
            icon="CheckSquareIcon"
            size="16"
          />
          <span class="align-middle ml-50">Todo test</span>
        </b-dropdown-item>

        <b-dropdown-item :to="{ name: 'apps-chat' }">
          <feather-icon
            icon="MessageSquareIcon"
            size="16"
          />
          <span class="align-middle ml-50">Chat</span>
        </b-dropdown-item>

        <b-dropdown-item :to="{ name: 'apps-email' }">
          <feather-icon
            icon="MailIcon"
            size="16"
          />
          <span class="align-middle ml-50">Email</span>
        </b-dropdown-item>

        <b-dropdown-item :to="{ name: 'apps-calendar' }">
          <feather-icon
            icon="CalendarIcon"
            size="16"
          />
          <span class="align-middle ml-50">Calendar</span>
        </b-dropdown-item>
      </b-dropdown>
    </b-col>
  </b-row>
</template>

<script>
import {
  BBreadcrumb, BBreadcrumbItem, BRow, BCol, BDropdown, BDropdownItem, BButton, BAvatar,
  BLink, BNavbarNav, BNavItemDropdown, BDropdownDivider,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'

export default {
  directives: {
    Ripple,
  },
  components: {
    BBreadcrumb,
    BBreadcrumbItem,
    BRow,
    BCol,
    BDropdown,
    BDropdownItem,
    BButton,
    BAvatar,
    BLink, BNavbarNav, BNavItemDropdown, BDropdownDivider,
  },
}
</script>
