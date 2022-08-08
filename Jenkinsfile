pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                sh 'git-ftp.url "160.153.55.233"'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing..'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying. revised....'
            }
        }
    }
}