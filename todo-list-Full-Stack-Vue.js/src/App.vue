<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const todos = ref([])
const input_content = ref('')
const editingTodo = ref(null)

onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/todo')
    todos.value = response.data
  } catch (error) {
    console.error('Error fetching todos:', error)
  }
})

const addTodo = async () => {
  if (input_content.value.trim() === '') {
    return
  }

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/todo', {
      text: input_content.value
    })
    todos.value.push(response.data)
  } catch (error) {
    console.error('Error adding todo:', error)
  }
}

const removeTodo = async (todo) => {
  try {
    await axios.delete(`http://127.0.0.1:8000/api/todo/${todo.id}`)
    todos.value = todos.value.filter(t => t !== todo)
  } catch (error) {
    console.error('Error deleting todo:', error)
  }
}

const editTodo = async (todo) => {
  editingTodo.value = todo
}

const updateTodo = async () => {
  if (!editingTodo.value) {
    return
  }

  try {
    const response = await axios.put(`http://127.0.0.1:8000/api/todo/${editingTodo.value.id}`, {
      text: editingTodo.value.text
    })
    const index = todos.value.findIndex(t => t.id === editingTodo.value.id)
    todos.value[index] = response.data
    editingTodo.value = null
  } catch (error) {
    console.error('Error updating todo:', error)
  }
}

const todos_asc = computed(() => todos.value.sort((a, b) => a.createdAt - b.createdAt))
</script>

<template>
  <main class="app">
    <section class="create-todo">
      <h3>CREATE A TODO</h3>
      <form id="new-todo-form" @submit.prevent="addTodo">
        <input type="text" name="content" id="content" placeholder="введити задачу" v-model="input_content" />
        <input type="submit" value="Add todo" />
      </form>
    </section>

    <section class="todo-list">
      <h3>TODO LIST</h3>
      <div class="list " id="todo-list">
        <div v-for="todo in todos_asc" :class="`todo-item ${todo.done && 'done'} `">
          <div class="flex" v-if="editingTodo === todo">
            <input type="text" v-model="todo.text" />
            <button class="save"  @click="updateTodo">Save</button>
			
          </div>
          <div class="flex" v-else>
            <div class="todo-content">
              <span>{{ todo.text }}</span>
            </div>
            <div class="actions">
              <button class="edit" @click="editTodo(todo)">Edit</button>
              <button class="delete" @click="removeTodo(todo)">Delete</button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
