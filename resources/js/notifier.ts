interface NotificationConfig {
    message: string;
    type?: string;
    duration?: number;
    position?: string;
}

class CustomNotification {
    message: string;
    config: NotificationConfig;
    element: any;

    constructor(config: NotificationConfig) {
        this.message = config.message;
        this.config = Object.assign(
            {
                type: "info",
                duration: 5000,
                position: "top-right",
            },
            config
        );

        this.element = this.buildNotification();
    }

    buildNotification() {
        let notification = document.createElement("div");
        notification.className = `notification ${this.config.type} ${this.config.position}`;

        // add icon
        let icon = document.createElement("div");
        icon.className = "icon";
        switch (this.config.type) {
            case "success":
                icon.innerHTML = this.successIcon();
                break;
            case "error":
                icon.innerHTML = this.errorIcon();
                break;
            case "warning":
                icon.innerHTML = this.warningIcon();
                break;
            case "info":
                icon.innerHTML = this.infoIcon();
                break;
        }
        notification.appendChild(icon);

        let span = document.createElement("span");
        span.className = "message";
        span.innerHTML = this.message;
        notification.appendChild(span);
        // add close button
        let closeBtn = document.createElement("button");
        closeBtn.innerHTML = "&times;";
        closeBtn.className = "close";
        closeBtn.onclick = () => {
            this.destroy();
        };
        notification.appendChild(closeBtn);
        return notification;
    }

    show() {
        // add fadeout animation
        setTimeout(() => {
            this.element.classList.add("fadeout");
        }, this.config.duration * 0.9);
        setTimeout(() => {
            this.destroy();
        }, this.config.duration * 0.9 + 300); // 300ms is the duration of the fadeout animation
        return this.element;
    }

    destroy() {
        this.element.remove();
    }

    // icon methods
    successIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
      </svg>
      `;
    }

    errorIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
      </svg>
      `;
    }

    warningIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
      </svg>`;
    }

    infoIcon() {
        return `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
      </svg>
      `;
    }
}

interface NotificationConfig extends NotificationOptions {
    type?: string;
    duration?: number;
    position?: string;
}

class Notifier {
    container: any;

    constructor() {
        // grab the container for notifications, if it doesn't exist, create it
        this.container = document.querySelector(".notifications-container");
        if (!this.container) {
            this.container = document.createElement("div");
            this.container.className = "notifications-container";
            document.body.appendChild(this.container);
        }
        this.addStyles();
    }

    notify(config: NotificationConfig) {
        let notification = new CustomNotification(config);
        this.container.appendChild(notification.show());
    }

    addStyles() {
        let style = document.createElement("style");
        style.innerHTML = `
        .notifications-container {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }

        .notification {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;

            max-width: 600px;

            z-index: 1000;
            padding: 10px 20px ;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            animation: slideIn 0.3s ease;
            transform: translateX(0);

            margin-bottom: 10px;
        }

        .fadeout {
            animation: fadeOut 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .notification.success {
            background-color: #d4edda;
            color: #155724;
        }

        .notification.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .notification.warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .notification.info {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .close {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 30px;
            color: #f00;
        }

        .close:hover {
            color: #f0f;
            shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .notification .icon {
            transform: scale(1.3);
            margin-right: 10px;
        }

        `;
        document.head.appendChild(style);
    }
}

export default Notifier;
