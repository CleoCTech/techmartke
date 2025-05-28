import { ref, provide, inject } from "vue"

const TOAST_SYMBOL = Symbol()

export function provideToast() {
  const toast = ref(null)

  const showToast = (message, type = "info", duration = 3000) => {
    toast.value = { message, type, duration }
  }

  provide(TOAST_SYMBOL, {
    toast,
    showToast,
  })
}

export function useToast() {
  const context = inject(TOAST_SYMBOL)

  if (!context) {
    throw new Error("useToast must be used within a component that has called provideToast")
  }

  return context
}

