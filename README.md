# Row 2: GitHub + Ansible + Kubernetes

Actual Laravel 12 source, a Docker image, Ansible environment automation,
Kubernetes deployments, a persistent MariaDB database using Laravel's MySQL
driver, and an HTTP endpoint that verifies a real database query.

## Run on Ubuntu

```bash
git clone https://github.com/abdulhadics/laravel-k8s-ansible.git
cd laravel-k8s-ansible
bash scripts/deploy.sh
```

Enter your Ubuntu sudo password at the prompt. Ansible installs prerequisites,
configures lightweight Kubernetes (K3s), builds and imports the application image,
generates Kubernetes Secret credentials, deploys the database and application,
and checks the exposed application with a real SELECT 1 database query.
Allow at least 4 GB free disk space before installation. Images and dependencies
need Internet access; a full disk cannot run this deployment.

For an existing working Minikube setup, select that backend instead:

```bash
bash scripts/deploy.sh -e cluster_backend=minikube
```

This is a local lab demonstration using PHP's development HTTP server.
The NodePort is the network entry point; Laravel renders the presentation HTML.

## Verification and submission evidence

For the default K3s deployment:

```bash
mkdir -p evidence
git remote -v | tee evidence/github.txt
git log -1 --oneline | tee -a evidence/github.txt
ansible-playbook --version | tee evidence/ansible.txt
sudo k3s kubectl get nodes | tee evidence/nodes.txt
sudo k3s kubectl -n laravel-lab get pods,svc,pvc -o wide | tee evidence/kubernetes.txt
curl --fail http://127.0.0.1:30080/health/db | tee evidence/database.json
curl --fail http://127.0.0.1:30080/ | tee evidence/application.html
```

Expected database verification (only after deployment succeeds):

```json
{"application":"Laravel","database":"connected"}
```

The database endpoint executes SELECT 1 through Laravel's configured database
connection. It returns HTTP 503 when unavailable. Kubernetes readiness checks
this endpoint; liveness checks Laravel's /up route. No database migration is
needed for the demonstration query.

Open http://VM-IP:30080 from a browser with network access to the VM.
With VirtualBox NAT, forward guest TCP 30080, or run from Windows:

```powershell
ssh -p 2222 -L 8080:127.0.0.1:30080 haxdi@127.0.0.1
```

Keep the tunnel open and visit http://localhost:8080 in the Windows browser.
Take screenshots of the browser page, successful Ansible PLAY RECAP, and the
pods/services/PVC output. Do not claim deployment success before checks pass.

For Minikube, replace sudo k3s kubectl with minikube kubectl --, and test
http://$(minikube ip):30080/health/db from Ubuntu.

## Assignment mapping

| Requirement | Implementation |
| --- | --- |
| Laravel stored on GitHub | Laravel source, Composer files, Dockerfile, manifests, Ansible playbook |
| Environment automation | Ansible installs prerequisites and configures Kubernetes |
| Kubernetes deployment | Laravel Deployment with two replicas and a NodePort Service |
| Database connectivity | MariaDB, ClusterIP mysql-service:3306, Secret credentials, Laravel database query |
| Persistence | Database PersistentVolumeClaim with the cluster's default local storage class |
| Application accessibility | NodePort 30080, readiness probes and Ansible HTTP/database assertion |

## Troubleshooting

```bash
df -h /
sudo k3s kubectl -n laravel-lab get events --sort-by=.lastTimestamp
sudo k3s kubectl -n laravel-lab logs deployment/laravel --tail=50
sudo k3s kubectl -n laravel-lab logs deployment/mysql --tail=50
```

Choose one runtime, K3s or Minikube. Each consumes disk space.
Keep existing data and images unless you have confirmed they can be removed.
