<template>
  <div >
    <button aria-label="Plus user"
    class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600 plususer"
        @click="addRow()" style="position: fixed; right: 0px; top: 20px;" >
      <PlusIcon style="margin-top: 5px; margin-left: 2px;"/> <span style="display: block; border-radius: 5px ; width: 105px;    border: 1px solid #959595;    padding: 8px 5px;">Add Section</span>
  </button>
    <div class="flex w-full" style="margin-top:30px;">
      <div class="min-h-screen flex overflow-x-scroll py-12">
        <div
          v-for="(column, index) in columns"
          :key="column.title"
          class="bg-gray-100 rounded-lg px-3 py-3 column-width rounded mr-4 kutu"
        >
          <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm anakolonlar">
            <label-edit :text="column.title" :eskiadi="column.id" :yeri="index" id="labeledit" v-on:text-updated-blur="textUpdated" v-on:text-updated-enter="textUpdated" placeholder="Kolon Adı"></label-edit>
            <button aria-label="Plus user"
            class="p-1 focus:outline-none focus:shadow-outline text-teal-500 hover:text-teal-600 plususer"
                @click="addcolon(column.id,index)"  >
              <PlusIcon/>
          </button>
          </p>
          <!-- Draggable component comes from vuedraggable. It provides drag & drop functionality -->
          <draggable :list="column.tasks" :animation="200" ghost-class="ghost-card" group="tasks" @change="onDraggableUpdate">
            <!-- Each element from here will be draggable and animated. Note :key is very important here to be unique both for draggable and animations to be smooth & consistent. -->
            <task-card
              v-for="(task) in column.tasks"
              :key="task.id"
              :task="task"
              class="mt-3 cursor-move"
            ></task-card>
            <!-- </transition-group> -->
          </draggable>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import { EditIcon, Trash2Icon, PlusIcon } from "vue-feather-icons";
import LabelEdit from 'label-edit';
import VueSweetalert2 from 'vue-sweetalert2';
import TaskCard from "./TaskCard.vue";
import 'sweetalert2/dist/sweetalert2.min.css';
import axios from 'axios';
Vue.config.productionTip = false
Vue.use(VueSweetalert2);
export default {
  name: "App",
  props:['bilgikolon'],
  components: {
    TaskCard,
    draggable,
    EditIcon,
    Trash2Icon,
    PlusIcon,
    LabelEdit
  },
  data() {
    return {
      eski:"",
      kolonu:"",
      kolonadi:"",
      gorevadi:"",
      columns: this.bilgikolon
    };
  },
  methods:{
    textUpdated: function(text,eadi,eyer){
      axios.post('/kolon-guncelle', {
        baslik:text,
        kolonkodu:eadi
      }).then(response => {
        this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    icon: 'success',
                    title: 'İşleminiz kaydedildi.',
                    timerProgressBar: true,
                    didOpen: (toast) => {
                      toast.addEventListener('mouseenter', this.$swal.stopTimer)
                      toast.addEventListener('mouseleave', this.$swal.resumeTimer)
                    }
                  });
      }).catch(error => {
          this.$swal('İşlem başarısız!', '', 'error');
      });
      this.text = text;
      this.columns[eyer].title=text;
    },
    onDraggableUpdate(event){
      /*dinamik hareket merkezi*/

      axios.post('/tasima', {
        veriler:Array.from(this.columns)
      }).then(response => {
        console.log(response.data);
      }).catch(error => {
          this.$swal('İşlem başarısız!', '', 'error');
      });
    },
    addRow(){
      this.$swal({
        title: 'Kolon Adı Yazınız',
        input: 'text',
        inputLabel: '',
        inputValue: this.kolonadi,
        showCancelButton: true,
        inputValidator: (value) => {
          if (!value) {
            return 'Kolon adı boş bırakılamaz.'
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          this.kolonadi=result.value;
          /*Şimdi eklemek için axios ile kolon adını gönder*/
          axios.post('/kolon-ekle', {
            baslik:result.value
          }).then(response => {
            this.$swal('Eklendi!', '', 'success');
            this.columns.push(
              {
                id:response.id,
                title: response.data.baslik,
                edit:false,
                slug: response.data.slug
              }
            );
          }).catch(error => {
              this.$swal('İşlem başarısız!', '', 'errors');
          });

          /*******************************/
        }
      });

      },
    addcolon(kolonid,index){
      this.$swal({
        title: 'Bir Görev Adı Yazınız',
        input: 'text',
        inputLabel: '',
        inputValue: this.gorevadi,
        showCancelButton: true,
        inputValidator: (value) => {
          if (!value) {
            return 'Görev adı boş bırakılamaz.'
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          this.gorevadi=result.value;
          /*Şimdi eklemek için axios ile kolon adını gönder*/
          axios.post('/sutun-ekle', {
            baslik:result.value,
            kolonno:kolonid
          }).then(response => {
            this.$swal('Eklendi!', '', 'success');
            this.columns[index].tasks.push(
              {
                id: response.id,
                title: response.data.baslik,
                slug: response.data.slug,
                metin: response.data.metin
              }
            );
          }).catch(error => {
              this.$swal('İşlem başarısız!', '', 'errors');
          });
        }
      });
    }
  }
};
</script>

<style scoped>
.column-width {
  min-width: 320px;
  width: 320px;
}
.cursor-move:hover{
  -webkit-box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.75);
box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.75);
}

.ghost-card {
  opacity: 0.5;
  background: #F7FAFC;
  border: 1px solid #4299e1;
}
.plususer{
  float: right;
margin-right: 8px;
background: none;
}
.plususer svg{float: left;}
.anakolonlar{
  clear: both;
    overflow: hidden;
}
.vlabeledit{
  display: block;
    float: left;
    width: 85%;
}
.vlabeledit-input{
  display: block;
  width: 100%;
}
.kutu{
background: #eef5fa;
padding: 0 8px;
margin-right: 10px;
}

</style>
