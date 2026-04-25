export {};

declare global {
  interface Window {
    s_confirm: () => Promise<boolean>;
    s_alert: (message: string) => void;
  }
}
