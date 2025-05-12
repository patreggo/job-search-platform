<template>
  <div class="flex justify-center items-center h-[70vh]">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <router-link to="/vacancies" class="block bg-blue-100 p-10 text-center rounded-lg shadow hover:bg-blue-200">
        <h2 class="text-2xl font-bold text-blue-800">Вакансии</h2>
      </router-link>
      <router-link to="/resumes" class="block bg-green-100 p-10 text-center rounded-lg shadow hover:bg-green-200">
        <h2 class="text-2xl font-bold text-green-800">Резюме</h2>
      </router-link>
    </div>
  </div>
</template>
