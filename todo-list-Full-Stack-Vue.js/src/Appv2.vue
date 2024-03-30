<script>
import axios from 'axios';

export default{
  data(){
    return{
      data: null,             //этот используется при загрузке и в нем все данные с api 
      input_value:'',         //данные с инпута 
      selected:null,
    }
  },
  methods:{
    value_editor(input_data){ //меняет че в инпуте засоввывает в input_value
      this.input_value = input_data
      console.log('щас в инпуте ->' + this.input_value)
    },
    update_todo(){
      this.data = null
      axios.get('http://127.0.0.1:8000/api/todo')
        .then(res => (this.data = res.data))
    },
    transmit(){                                     //при нажатии кнопки дергается эта функция 
      axios.post('http://127.0.0.1:8000/api/todo',{ //здесь все само себя объясняет если есть 
        text: this.input_value                      //все вопросы на https://axios-http.com/docs/post_example
      })
      setTimeout(this.update_todo, 200)            //😭оно обновляет туду через 100ms 
      console.log(this.input_value + "<---передан в Api наверное? проверь") //проверка что-бы наверняка
    },
    remove(id){
      axios.delete(`http://127.0.0.1:8000/api/todo/${id}`)
      setTimeout(this.update_todo, 200)
      console.log("функция римувал завершила работу")
    },
    change(id){
      axios.put(`http://127.0.0.1:8000/api/todo/${id}`, {
      text: this.input_value
    })
    setTimeout(this.update_todo, 200)
    console.log("функция эдит завершила работу")
    }
  },
  mounted: function () {      //при загрузке выполняет все что внизу
    this.$nextTick(function () {                    //при загрузке получает с api данные и кидает в data
        axios.get('http://127.0.0.1:8000/api/todo')
        .then(res => (this.data = res.data))        //но data выглядит ужасно оно не array а object (меня пугает это)😭😭😭
    })
  }
}
</script>
<template>
<div class="data_grid">
  <div class="buttons">
    <button type="button" @click="remove(this.selected)">REMOVE</button>
    <button type="button" @click="change(this.selected)">EDIT</button>
    <button type="button" @click="transmit()">ADD</button>
  </div>
  <div class="data_control">
    <div class="data_container">
      <div v-for="item in data">                      <!--дергает data и каждый элемент отдельным блоком вставляет из него-->
        <p @click="this.selected = item.id">{{ item.id }} - {{ item.text }}</p>
      </div>
    </div>
    <div class="insert">
      <input type="text" @input="value_editor($event.target.value)" placeholder="TYPE HERE!!!">
    </div>
  </div>  
</div>
</template>

<style scoped>
.data_grid {
  margin: 0 auto;
  border: 8px double;
  width: 1000px;
  height: 500px;
  display: grid;
  grid-template-columns: 1fr 4fr;
  background-color: grey;
}
.data_container{
  margin: 12px;
  background-color: white;
  display: flex;
  
  flex-direction: column;
  overflow: overlay;
}
.data_control{
  display: grid;
  grid-template-rows: 2fr 1fr;
  overflow: overlay;
}
.buttons{
  display: grid;
  grid-template-rows: 1fr 1fr 1fr;
}
button{
  margin: 12px;
  background-color: lightgray;
  border: 8px outset;
  font-weight: 600;
  font-size:xx-large;
}
button:active{
  border: 8px inset;
}
p{
  width: -webkit-fill-available;
  height: min-content;
  margin: 12px;
  background-color: lightgray;
  padding: 4px;
  border: none;
  font-weight: 400;
  font-size: medium;
}
input{
  margin: 12px;
  width: -webkit-fill-available;
  height: -webkit-fill-available;
  padding: 4px;
  font-weight: 600;
  font-size:xx-large;
}
.selector{
position: relative;
}
</style>
