<template>
  <div class="bg-white sutunumuz shadow rounded px-3 pt-3 pb-5 border border-white" style="margin: 9px 5px;border-radius: 5px; padding: 5px 5px;">
    <div class="flex justify-between">
      <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm sutunbaslik">{{task.title}}</p>
      <a class="islemler"><Edit2Icon/></a>
      <a class="islemler"><Trash2Icon/></a>
    </div>
    <div class="flex mt-4 justify-between items-center">
      <span class="text-sm text-gray-600">{{task.metin}}</span>
      <badge v-if="task.type" :color="badgeColor">{{task.type}}</badge>
    </div>
  </div>
</template>
<script>
import Badge from "./Badge.vue";
import { Edit2Icon, Trash2Icon } from "vue-feather-icons";
export default {
  components: {
    Badge,
    Trash2Icon,
    Edit2Icon
  },
  props: {
    task: {
      type: Object,
      default: () => ({})
    }
  },
  computed: {
    badgeColor() {
      const mappings = {
        Design: "purple",
        "Feature Request": "teal",
        Backend: "blue",
        QA: "green",
        default: "teal"
      };
      return mappings[this.task.type] || mappings.default;
    }
  }
};
</script>
<style>
.sutunbaslik{
  max-width: 80%;
  float: left;
  min-width: 80%;
}
.islemler svg{
  width: 14px;
    height: 14px;
    margin-left: 8px;
    margin-right: 8px;
    margin-top: 15px;
}
.islemler{
  display: none;
}
.sutunumuz:hover .islemler{
  display: block !important;
  cursor: pointer;
}
</style>
